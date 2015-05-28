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
            ->getRepository('MusicBundle\Entity\MediaVariant')
            ->find($id);

        if (!$variant) {
            throw $this->createNotFoundException('Variant not found');
        }

        $storage = $this->get('payum')
            ->getStorage('MusicBundle\Entity\PaymentDetails');

        $details = $storage->create();
        $details['PAYMENTREQUEST_0_CURRENCYCODE'] = 'GBP';
        $details['PAYMENTREQUEST_0_AMT'] = $variant->getPrice();
        $storage->update($details);

        $captureToken = $this->get('payum.security.token_factory')->createCaptureToken(
            self::GATEWAY_NAME,
            $details,
            'music_order_complete' // the route to redirect after capture;
        );

        return $this->redirect($captureToken->getTargetUrl());
    }

    public function completeAction(Request $request)
    {
        $token = $this->get('payum.security.http_request_verifier')->verify($request);

        $identity = $token->getDetails();
        $model = $this->get('payum')->getStorage($identity->getClass())->find($identity);

        $gateway = $this->get('payum')->getGateway($token->getGatewayName());

        // you can invalidate the token. The url could not be requested any more.
        // $this->get('payum.security.http_request_verifier')->invalidate($token);

        // Once you have token you can get the model from the storage directly.
        //$identity = $token->getDetails();
        //$details = $payum->getStorage($identity->getClass())->find($identity);

        // or Payum can fetch the model for you while executing a request (Preferred).
        $gateway->execute($status = new GetHumanStatus($token));
        $details = $status->getFirstModel();

        // you have order and payment status
        // so you can do whatever you want for example you can just print status and payment details.

        return new JsonResponse(array(
            'status' => $status->getValue(),
            'details' => iterator_to_array($details),
        ));
    }
}
