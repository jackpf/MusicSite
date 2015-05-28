<?php

namespace MusicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
        $order['PAYMENTREQUEST_0_AMT']          = $order->getPrice() + 3.30;
        $order['PAYMENTREQUEST_0_SHIPPINGAMT']  = 3.30;

        $storage->update($order);

        $captureToken = $this->get('payum.security.token_factory')->createCaptureToken(
            self::GATEWAY_NAME,
            $order,
            'music_order_purchase' // the route to redirect after capture;
        );

        return $this->redirect($captureToken->getTargetUrl());
    }

    public function purchaseAction(Request $request)
    {
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

        // you have order and payment status
        // so you can do whatever you want for example you can just print status and payment details.

        return $this->render('MusicBundle:Music:order_complete.html.twig', [
            'order' => $order,
        ]);
    }
}
