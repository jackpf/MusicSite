<?php

namespace MusicBundle\Entity;

class ReleaseType
{
    private $id;

    private $name;

    private $shippable;

    private $shippingPrice;

    private $description;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getShippable()
    {
        return $this->shippable;
    }

    public function setShippable($shippable)
    {
        $this->shippable = $shippable;
    }

    public function getShippingPrice()
    {
        return $this->shippingPrice;
    }

    public function setShippingPrice($shippingPrice)
    {
        $this->shippingPrice = $shippingPrice;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function __toString()
    {
        return $this->getName();
    }
}