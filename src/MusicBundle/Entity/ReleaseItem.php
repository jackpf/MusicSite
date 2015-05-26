<?php

namespace MusicBundle\Entity;

class ReleaseItem extends MediaItem
{
    private $mediaFiles = [];

    private $mediaVariants;

    public function getMediaFiles()
    {
        return $this->mediaFiles;
    }

    public function setMediaFiles($mediaFiles)
    {
        $this->mediaFiles = $mediaFiles;
    }

    public function getMediaVariants()
    {
        return $this->mediaVariants;
    }

    public function setMediaVariants($mediaVariants)
    {
        $this->mediaVariants = $mediaVariants;
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