<?php

namespace MusicBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class ReleaseItem extends MediaItem
{
    private $mediaFiles;

    private $mediaVariants;

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

    public function addMediaFile($mediaFile)
    {
        $this->mediaFiles[] = $mediaFile;
    }

    public function removeMediaFile($mediaFile)
    {
        foreach ($this->mediaFiles as $key => $file) {
            if ($mediaFile->getId() == $file->getId()) {
                unset($this->mediaFiles[$key]);
            }
        }
    }

    public function getMediaVariants()
    {
        return $this->mediaVariants;
    }

    public function setMediaVariants($mediaVariants)
    {
        $this->mediaVariants = $mediaVariants;
    }

    public function addMediaVariant($mediaVariant)
    {
        $this->mediaVariants[] = $mediaVariant;
    }

    public function removeMediaVariant($mediaVariant)
    {
        foreach ($this->mediaVariant as $key => $variant) {
            if ($mediaVariant->getId() == $variant->getId()) {
                unset($this->mediaVariant[$key]);
            }
        }
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