<?php

namespace MusicBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class ReleaseItem extends MediaItem
{
    private $mediaFiles;

    private $releaseVariants;

    public function __construct()
    {
        $this->mediaFiles = new ArrayCollection();
        $this->releaseVariants = new ArrayCollection();
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
        return $this->releaseVariants;
    }

    public function setReleaseVariants($releaseVariants)
    {
        $this->releaseVariants = $releaseVariants;
    }

    public function addReleaseVariant($releaseVariants)
    {
        $this->releaseVariants[] = $releaseVariants;
    }

    public function removeReleaseVariant($releaseVariants)
    {
        foreach ($this->releaseVariants as $key => $variant) {
            if ($releaseVariants->getId() == $variant->getId()) {
                unset($this->releaseVariants[$key]);
            }
        }
    }

    public function onPreFlush()
    {
        // Doctrine doesn't seem to be setting the inverse relation, currently unsupported by doctrine?
        foreach (array_merge($this->mediaFiles->toArray(), $this->releaseVariants->toArray()) as $object) {
            if ($object) {
                $object->setMediaItem($this);
            }
        }
    }

    public function __toString()
    {
        return $this->getTitle();
    }
}