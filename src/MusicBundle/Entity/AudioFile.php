<?php

namespace MusicBundle\Entity;

use MusicBundle\Data\Data;
use MusicBundle\Service\DownloadManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AudioFile extends MediaFile
{
    private $name;

    private $previewPath;

    private $mp3Path;

    private $previewFile;

    private $mp3File;

    public function getPreviewPath()
    {
        return $this->previewPath;
    }

    public function getMp3Path()
    {
        return $this->mp3Path;
    }

    public function setMp3Path($mp3Path)
    {
        $this->mp3Path = $mp3Path;
    }

    public function setPreviewPath($previewPath)
    {
        $this->previewPath = $previewPath;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getPreviewFile()
    {
        return $this->previewFile;
    }

    public function setPreviewFile($previewFile)
    {
        $this->previewFile = $previewFile;
    }

    public function getMp3File()
    {
        return $this->mp3File;
    }

    public function setMp3File(UploadedFile $mp3File = null)
    {
        $this->mp3File = $mp3File;
    }
}