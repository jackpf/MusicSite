<?php

namespace MusicBundle\Entity;

class VideoCastItem extends MediaItem
{
    private $videoFile;

    /**
     * @return mixed
     */
    public function getVideoFile()
    {
        return $this->videoFile;
    }

    /**
     * @param mixed $videoFile
     */
    public function setVideoFile($videoFile)
    {
        $this->videoFile = $videoFile;
    }

    public function getMediaFiles()
    {
        return [$this->getVideoFile()];
    }
}