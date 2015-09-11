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

    private $createdAt;

    private $updatedAt;

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
}