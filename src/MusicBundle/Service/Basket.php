<?php

namespace MusicBundle\Service;

use Doctrine\Common\Collections\ArrayCollection;
use MusicBundle\Entity\ReleaseVariant;
use Symfony\Component\HttpFoundation\Session\Session;

class Basket
{
    const SESSION_KEY = 'basket';

    private $session;

    private $objects;

    public function __construct(Session $session)
    {
        $this->session = $session;
        $this->objects = new ArrayCollection();
        $this->load();
    }

    public function load()
    {
        if (($objects = $this->session->get(self::SESSION_KEY)) instanceof ArrayCollection) {
            $this->objects = $objects;
        }
    }

    public function store()
    {
        $this->session->set(self::SESSION_KEY, $this->objects);
    }

    public function add($object)
    {
        $this->objects->add($object);
    }

    public function remove($object)
    {
        $this->objects->removeElement($object);
    }

    public function getAll()
    {
        return $this->objects;
    }

    public function size()
    {
        return $this->objects->count();
    }

    public function contains($objectToFind)
    {
        foreach ($this->objects as $object) {
            if ($object->getId() == $objectToFind->getId()) {
                return true;
            }
        }

        return false;
    }

    public function clear()
    {
        $this->objects = new ArrayCollection();
    }
}