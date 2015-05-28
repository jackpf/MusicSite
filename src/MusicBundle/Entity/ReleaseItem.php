<?php

namespace MusicBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class ReleaseItem extends MediaItem
{
    private $mediaFiles;

    private $ReleaseVariants;

    public function __construct()
    {
        $this->mediaFiles = new ArrayCollection();
        $this->ReleaseVariants = new ArrayCollection();
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

    public function getReleaseVariants()
    {
        return $this->ReleaseVariants;
    }

    public function setReleaseVariants($ReleaseVariants)
    {
        $this->ReleaseVariants = $ReleaseVariants;
    }

    public function addReleaseVariant($ReleaseVariant)
    {
        $this->ReleaseVariants[] = $ReleaseVariant;
    }

    public function removeReleaseVariant($ReleaseVariant)
    {
        foreach ($this->ReleaseVariant as $key => $variant) {
            if ($ReleaseVariant->getId() == $variant->getId()) {
                unset($this->ReleaseVariant[$key]);
            }
        }
    }

    public function onPreFlush()
    {
        // Doctrine doesn't seem to be setting the inverse relation
        foreach (array_merge($this->mediaFiles->toArray(), $this->ReleaseVariants->toArray()) as $object) {
            if ($object) {
                $object->setMediaItem($this);
            }
        }
    }
}