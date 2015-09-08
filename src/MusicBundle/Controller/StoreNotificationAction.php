<?php

namespace MusicBundle\Controller;

use Payum\Core\Action\GatewayAwareAction;
use Payum\Core\Request\Notify;

class StoreNotificationAction extends GatewayAwareAction
{
    protected $notificationStorage;

    public function __constructor(StorageInterface $notificationStorage)
    {
        $this->notificationStorage = $notificationStorage;
    }

    public function execute($request)
    {
        $notification = $this->notificationStorage->create();

        $this->gateway->execute($getHttpRequest = new GetHttpRequest);

        foreach ($getHttpRequest->query as $name => $value) {
            $notification[$name] = $value;
        }
        foreach ($getHttpRequest->request as $name => $value) {
            $notification[$name] = $value;
        }

        $this->notificationStorage->update($notification);
    }

    public function supports($request)
    {
        return $request instanceof Notify;
    }
}