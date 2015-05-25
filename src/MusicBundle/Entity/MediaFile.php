<?php

namespace MusicBundle\Entity;

use MusicBundle\Data\Data;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaFile
{
    private $id;

    private $name;

    private $path;

    private $mediaItem;

    private $file;

    public function getId()
    {
        return $this->id;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getMediaItem()
    {
        return $this->mediaItem;
    }

    public function setMediaItem($mediaItem)
    {
        $this->mediaItem = $mediaItem;
    }

    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function lifecycleFileUpload()
    {
        if (!$this->getFile()) {
            return;
        }

        $original = $this->getFile()->getClientOriginalName();
        $parts = explode('.', $original);
        $ext = end($parts);
        $path = sha1($original + rand()) . '.' . $ext;

        $this->getFile()->move(
            Data::UPLOAD_DIR,
            $path
        );

        $this->setPath($path);
        $this->setFile(null);
    }
}