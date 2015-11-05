<?php

namespace MusicBundle\Controller;

use Payum\Core\Action\GatewayAwareAction;
use Payum\Core\Request\Notify;
use Payum\Core\Storage\StorageInterface;
use Payum\Core\Request\GetHttpRequest;

class StoreNotificationAction extends GatewayAwareAction
{
    protected $notificationStorage;

    public function __constructor(StorageInterface $notificationStorage)
    {
        $this->notificationStorage = $notificationStorage;
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
    }

    public function supports($request)
    {
        return $request instanceof Notify;
    }
}