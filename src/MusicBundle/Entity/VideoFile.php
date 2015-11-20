<?php

namespace MusicBundle\Entity;

use MusicBundle\Data\Data;
use MusicBundle\Service\DownloadManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class VideoFile extends MediaFile
{
    private $processedPath;

    private $audioPath;

    private $iconPath;

    /**
     * @return mixed
     */
    public function getProcessedPath()
    {
        return $this->processedPath;
    }

    /**
     * @param mixed $processedPath
     */
    public function setProcessedPath($processedPath)
    {
        $this->processedPath = $processedPath;
    }

    /**
     * @return mixed
     */
    public function getAudioPath()
    {
        return $this->audioPath;
    }

    /**
     * @param mixed $audioPath
     */
    public function setAudioPath($audioPath)
    {
        $this->audioPath = $audioPath;
    }

    /**
     * @return mixed
     */
    public function getIconPath()
    {
        return $this->iconPath;
    }

    /**
     * @param mixed $iconPath
     */
    public function setIconPath($iconPath)
    {
        $this->iconPath = $iconPath;
    }
}