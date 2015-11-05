<?php

namespace MusicBundle\Controller;

use MusicBundle\Entity\Order;
use Payum\Core\Action\GatewayAwareAction;
use Payum\Core\Request\Notify;
use Payum\Core\Request\GetHttpRequest;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Payum\Core\Request\GetHumanStatus;
use Symfony\Component\EventDispatcher\GenericEvent;

class StoreNotificationAction extends GatewayAwareAction
{
    protected $container;

    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    private function verify($notification)
    {
        $postData = 'cmd=_notify-validate';

        foreach ($notification as $key => $value) {
            $postData .= '&' . $key . '=' . urlencode($value);
        }

        $ch = curl_init();

        if (!isset($notification['test_ipn'])) {
            throw new \RuntimeException('Unable to determine test mode');
        }

        curl_setopt($ch, CURLOPT_URL, $notification['test_ipn'] ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        return curl_exec($ch) == 'VERIFIED';
    }

    private function updateOrderStatus(Order $order)
    {
        switch ($order->getNotification()['payment_status']) {
            case 'Completed':
                $order->setStatus(GetHumanStatus::STATUS_AUTHORIZED);
            break;
            case 'Denied': case 'Failed': case 'Voided':
                $order->setStatus(GetHumanStatus::STATUS_FAILED);
            break;
            case 'Expired':
                $order->setStatus(GetHumanStatus::STATUS_EXPIRED);
            break;
            case 'Pending':
                $order->setStatus(GetHumanStatus::STATUS_PENDING);
            break;
            case 'Refunded':
                $order->setStatus(GetHumanStatus::STATUS_REFUNDED);
            break;
            case 'Reversed':
                $order->setStatus('reversed' /* no existing state for this */);
            break;
            case 'Processed':
                $order->setStatus(GetHumanStatus::STATUS_PENDING);
            break;
            default:
                $order->setStatus(GetHumanStatus::STATUS_UNKNOWN);
            break;
        }
    }

    public function execute($request)
    {
        $order = $request->getModel();

        $this->gateway->execute($getHttpRequest = new GetHttpRequest);

        $notification = [];

        foreach (array_merge($getHttpRequest->query, $getHttpRequest->request) as $name => $value) {
            $notification[$name] = $value;
        }

        $order->setNotification($notification);

        if (!$this->verify($order->getNotification())) {
            throw new \RuntimeException('PayPal did not verify this notification');
        }

        $this->updateOrderStatus($order);

        $this->container->get('doctrine.orm.entity_manager')
            ->flush();

        $this->container->get('event_dispatcher')
            ->dispatch('event.notification', new GenericEvent(null, ['order' => $order]));
    }

    public function supports($request)
    {
        return $request instanceof Notify;
    }
}