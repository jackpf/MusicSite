<?php

namespace MusicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;
use Payum\Core\Request\GetHumanStatus;
use Symfony\Component\HttpFoundation\JsonResponse;

class PaymentController extends Controller
{
    const GATEWAY_NAME = 'paypal';

    public function orderAction($id)
    {
        $variant = $this->getDoctrine()->getEntityManager()
            ->getRepository('MusicBundle\Entity\ReleaseVariant')
            ->find($id);

        if (!$variant) {
            throw $this->createNotFoundException('Variant not found');
        }

        $storage = $this->get('payum')
            ->getStorage('MusicBundle\Entity\Order');

        /** @var \MusicBundle\Entity\Order $order */
        $order = $storage->create();

        $order->setUser($this->get('security.context')->getToken()->getUser());
        $order->setReleaseVariant($variant);
        $order->setPrice($variant->getPrice());

        $order['L_PAYMENTREQUEST_0_NAM0']       = $order->getReleaseVariant()->getMediaItem()->getTitle();
        $order['L_PAYMENTREQUEST_0_NUMBER0']    = $order->getReleaseVariant()->getMediaItem()->getId();
        $order['L_PAYMENTREQUEST_0_AMT0']       = $order->getPrice();
        $order['L_PAYMENTREQUEST_0_QTY0']       = 1;
        $order['PAYMENTREQUEST_0_CURRENCYCODE'] = 'GBP';
        $order['PAYMENTREQUEST_0_ITEMAMT']      = $order->getPrice();
        $order['PAYMENTREQUEST_0_AMT']          = $order->getPrice() + $order->getReleaseVariant()->getType()->getShippingPrice();
        $order['PAYMENTREQUEST_0_SHIPPINGAMT']  = $order->getReleaseVariant()->getType()->getShippingPrice();

        $notifyToken = $this->get('payum.security.token_factory')
            ->createNotifyToken(self::GATEWAY_NAME, $order);
        $order['NOTIFYURL'] = $notifyToken->getTargetUrl();

        $storage->update($order);

        if ($variant->getPrice() == 0) {
            $route = $this->get('router')->generate('music_order_purchase', ['id' => $order->getId()]);
        } else {
            $route = $this->get('payum.security.token_factory')->createCaptureToken(
                self::GATEWAY_NAME,
                $order,
                'music_order_purchase' // the route to redirect after capture;
            )->getTargetUrl();
        }

        return $this->redirect($route);
    }

    public function purchaseAction(Request $request, $id = null)
    {
        // Free download?
        if ($id != null) {
            $order = $this->getDoctrine()->getManager()
                ->getRepository('MusicBundle\Entity\Order')
                ->find($id);

            if ($order && $order->getReleaseVariant()->getPrice() == 0) {
                $order->setStatus(GetHumanStatus::STATUS_AUTHORIZED);

                $this->getDoctrine()->getEntityManager()
                    ->flush();

                $this->get('event_dispatcher')->dispatch('event.order', new GenericEvent(null, ['order' => $order]));

                return $this->render('MusicBundle:Music:order_complete.html.twig', [
                    'order' => $order,
                ]);
            }
        }

        // Not free!
        $token = $this->get('payum.security.http_request_verifier')
            ->verify($request);
        $gateway = $this->get('payum')->getGateway($token->getGatewayName());
        $identity = $token->getDetails();

        $this->get('payum.security.http_request_verifier')
            ->invalidate($token);

        $order = $this->get('payum')
            ->getStorage($identity->getClass())
            ->find($identity);

        // or Payum can fetch the model for you while executing a request (Preferred).
        $gateway->execute($status = new GetHumanStatus($token));

        /** @var \MusicBundle\Entity\Order $order */
        $order = $status->getFirstModel();
        $order->setStatus($status->getValue());

        $this->getDoctrine()->getEntityManager()
            ->flush();

        $this->get('event_dispatcher')->dispatch('event.order', new GenericEvent(null, ['order' => $order]));

        // you have order and payment status
        // so you can do whatever you want for example you can just print status and payment details.

        return $this->render('MusicBundle:Music:order_complete.html.twig', [
            'order' => $order,
        ]);
    }
}
