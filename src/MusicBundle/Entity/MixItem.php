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
        foreach ($this->mediaFiles->toArray() as $object) {
            if ($object) {
                $object->setMediaItem($this);
            }
        }
    }

    public function getType()
    {
        return 'mix';
    }
}