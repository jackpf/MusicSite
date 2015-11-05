<?php

namespace MusicBundle\Entity;

use Payum\Core\Model\ArrayObject;
use Payum\Core\Request\GetHumanStatus;

class Order extends ArrayObject
{
    private $id;

    private $user;

    private $releaseVariant;

    private $price;

    private $status = GetHumanStatus::STATUS_NEW;

    private $notification;

    private $createdAt;

    private $updatedAt;

    private $dispatchStatus;

    const DISPATCH_STATUS_PROCESSING    = 0,
        DISPATCH_STATUS_DISPATCHED      = 1,
        DISPATCH_STATUS_UNDISPATCHABLE  = 2;

    public static $DISPATCH_STATUS = [
        self::DISPATCH_STATUS_PROCESSING      => 'processing',
        self::DISPATCH_STATUS_DISPATCHED      => 'dispatched',
        self::DISPATCH_STATUS_UNDISPATCHABLE  => 'n/a'
    ];

    public function getId()
    {
        return $this->id;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getReleaseVariant()
    {
        return $this->releaseVariant;
    }

    public function setReleaseVariant($releaseVariant)
    {
        $this->releaseVariant = $releaseVariant;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getNotification()
    {
        return $this->notification;
    }

    public function setNotification(array $notification = null)
    {
        $this->notification = $notification;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    public function getDetails()
    {
        $details = '';

        foreach ($this as $key => $value) {
           $details .= $key . ': ' . $value . "\n";
        }

        return $details;
    }

    public function setDispatchStatus($dispatchStatus)
    {
        $this->dispatchStatus = $dispatchStatus;
    }

    public function getDispatchStatus()
    {
        return $this->dispatchStatus;
    }

    public function getDispatchStatusString()
    {
        return self::$DISPATCH_STATUS[$this->getDispatchStatus()];
    }
}