<?php

namespace MusicBundle\Entity;

use MusicBundle\Data\Data;
use MusicBundle\Service\DownloadManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class VideoFile extends MediaFile
{
    private $processedPath;

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