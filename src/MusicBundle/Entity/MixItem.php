<?php

namespace MusicBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class MixItem extends MediaItem
{
    private $mediaFiles;

    private $downloadLink;

    public function __construct()
    {
        $this->mediaFiles = new ArrayCollection();
        $this->mediaVariants = new ArrayCollection();
    }

    public function getMediaFiles()
    {
        return $this->mediaFiles;
    }

    public function setMediaFiles($mediaFiles)
    {
        $this->mediaFiles = $mediaFiles;
    }

    public function getDownloadLink()
    {
        return $this->downloadLink;
    }

    public function setDownloadLink($downloadLink)
    {
        $this->downloadLink = $downloadLink;
    }

    public function onPreFlush()
    {
        // Doctrine doesn't seem to be setting the inverse relation
        foreach (array_merge($this->mediaFiles->toArray(), $this->mediaVariants->toArray()) as $object) {
            if ($object) {
                $object->setMediaItem($this);
            }
        }
    }
}