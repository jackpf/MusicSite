<?php

namespace MusicBundle\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use MusicBundle\Data\Data;
use MusicBundle\Service\DownloadManager;

abstract class MediaFile
{
    protected $id;

    protected $mediaItem;

    protected $path;

    protected $file;

    protected $createdAt;

    protected $updatedAt;

    public function getId()
    {
        return $this->id;
    }

    public function getMediaItem()
    {
        return $this->mediaItem;
    }

    public function setMediaItem($mediaItem)
    {
        $this->mediaItem = $mediaItem;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        $this->setUpdatedAt(new \DateTime());
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    public function delete($filename)
    {
        $path = Data::getUploadPath() . '/' . $filename;

        if ($filename != null && file_exists($path)) {
            unlink($path);
        }
    }

    public function lifecycleFileUpload()
    {
        if ($this->getFile()) {
            $this->delete($this->getPath());

            $path = DownloadManager::createPath($this->getFile()->getClientOriginalName());

            $this->getFile()->move(
                Data::getUploadPath(),
                $path
            );
            $this->setPath($path);
        }
    }

    public function lifecycleFileDelete()
    {
        $this->delete($this->getPath());
    }
}
