<?php

namespace MusicBundle\Entity;

class ReleaseType
{
    private $id;

    private $name;

    private $shippable;

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

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }
}