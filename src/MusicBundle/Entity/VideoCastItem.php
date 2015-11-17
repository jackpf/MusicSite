<?php

namespace MusicBundle\Entity;

class VideoCastItem extends MediaItem
{
    private $videoFile;

    private $useVideoIconAsImage;

    private $audioDownloadLink;

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

    /**
     * @return mixed
     */
    public function getUseVideoIconAsImage()
    {
        return $this->useVideoIconAsImage;
    }

    /**
     * @param mixed $useVideoIconAsImage
     */
    public function setUseVideoIconAsImage($useVideoIconAsImage)
    {
        $this->useVideoIconAsImage = $useVideoIconAsImage;
    }

    public function getImage()
    {
        if ($this->getUseVideoIconAsImage() && $this->getVideoFile() != null && $this->getVideoFile()->getIconPath() != null) {
            return $this->getVideoFile()->getIconPath();
        } else {
            return parent::getImage();
        }
    }

    public function getBackground()
    {
        if ($this->getUseVideoIconAsImage() && $this->getVideoFile() != null && $this->getVideoFile()->getIconPath() != null) {
            return $this->getVideoFile()->getIconPath();
        } else {
            return parent::getBackground();
        }
    }

    /**
     * @return mixed
     */
    public function getAudioDownloadLink()
    {
        return $this->audioDownloadLink;
    }

    /**
     * @param mixed $audioDownloadLink
     */
    public function setAudioDownloadLink($audioDownloadLink)
    {
        $this->audioDownloadLink = $audioDownloadLink;
    }
}