<?php

namespace MusicBundle\Entity;

use MusicBundle\Entity\Data\ReleaseVariantTypes;

class ReleaseVariant
{
    private $id;

    private $mediaItem;

    private $type;

    private $price = 0.0;

    private $isAvailable;

    public function getId()
    {
        return $this->id;
    }

    public function getMediaItem()
    {
        return $this->mediaItem;
    }

    public function setMediaItem($mediaItem)
    {
        $this->mediaItem = $mediaItem;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getIsAvailable()
    {
        return $this->isAvailable;
    }

    public function setIsAvailable($isAvailable)
    {
        $this->isAvailable = $isAvailable;
    }
}