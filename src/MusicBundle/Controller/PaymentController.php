<?php

namespace MusicBundle\Controller;

use MusicBundle\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;
use Payum\Core\Request\GetHumanStatus;
use Symfony\Component\EventDispatcher\GenericEvent;

class PaymentController extends Controller
{
    const GATEWAY_NAME = 'paypal';

    public function orderAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $variant = $em->getRepository('MusicBundle:ReleaseVariant')
            ->find($id);

        if (!$variant) {
            throw $this->createNotFoundException('Variant not found');
        }

        $storage = $this->get('payum')
            ->getStorage('MusicBundle\Entity\Order');

        $order = $storage->create();

        $order->setUser($this->get('security.context')->getToken()->getUser());
        $order->setReleaseVariant($variant);
        $order->setPrice($variant->getPrice());
        if ($variant->getType()->getShippable()) {
            $order->setDispatchStatus(Order::DISPATCH_STATUS_PROCESSING);
        } else {
            $order->setDispatchStatus(Order::DISPATCH_STATUS_UNDISPATCHABLE);
        }

        $order['L_PAYMENTREQUEST_0_NAM0']       = $order->getReleaseVariant()->getMediaItem()->getTitle();
        $order['L_PAYMENTREQUEST_0_NUMBER0']    = $order->getReleaseVariant()->getMediaItem()->getId();
        $order['L_PAYMENTREQUEST_0_AMT0']       = $order->getPrice();
        $order['L_PAYMENTREQUEST_0_QTY0']       = 1;
        $order['PAYMENTREQUEST_0_CURRENCYCODE'] = 'GBP';
        $order['PAYMENTREQUEST_0_ITEMAMT']      = $order->getPrice();
        $order['PAYMENTREQUEST_0_AMT']          = $order->getPrice() + $order->getReleaseVariant()->getType()->getShippingPrice();
        $order['PAYMENTREQUEST_0_SHIPPINGAMT']  = $order->getReleaseVariant()->getType()->getShippingPrice();

        $storage->update($order); // Need to store order before notification token is generated

        $order['NOTIFYURL'] = $this->get('payum.security.token_factory')
            ->createNotifyToken(self::GATEWAY_NAME, $order)->getTargetUrl();

        $storage->update($order);

        if ($variant->getPrice() == 0) {
            $route = $this->get('router')->generate('music_order_purchase', ['id' => $order->getId()]);
        } else {
            $route = $this->get('payum.security.token_factory')->createCaptureToken(
                self::GATEWAY_NAME,
                $order,
                'music_order_purchase'
            )->getTargetUrl();
        }

        return $this->redirect($route);
    }

    public function purchaseAction(Request $request, $id = null)
    {
        $em = $this->getDoctrine()->getManager();
        $freePurchase = false;

        // Free download?
        if ($id != null) {
            $order = $em->getRepository('MusicBundle:Order')
                ->find($id);

            if ($order && $order->getReleaseVariant()->getPrice() == 0) {
                $order->setStatus(GetHumanStatus::STATUS_AUTHORIZED);
                $freePurchase = true;
            }
        }

        // Not free!
        if (!$freePurchase) {
            $token = $this->get('payum.security.http_request_verifier')
                ->verify($request);
            $gateway = $this->get('payum')->getGateway($token->getGatewayName());
            $identity = $token->getDetails();

            $this->get('payum.security.http_request_verifier')
                ->invalidate($token);

            // or Payum can fetch the model for you while executing a request (Preferred).
            $gateway->execute($status = new GetHumanStatus($token));

            /** @var \MusicBundle\Entity\Order $order */
            $order = $status->getFirstModel();
            $order->setStatus($status->getValue());
        }

        $em->flush();

        if (in_array($order->getStatus(), [GetHumanStatus::STATUS_CAPTURED, GetHumanStatus::STATUS_AUTHORIZED, GetHumanStatus::STATUS_NEW, GetHumanStatus::STATUS_PENDING])) {
            $this->get('event_dispatcher')
                ->dispatch('event.order', new GenericEvent(null, ['order' => $order]));
        }

        return $this->render('MusicBundle:Music:order_complete.html.twig', [
            'order' => $order,
        ]);
    }
}
