<?php

namespace MusicBundle\Entity;

use MusicBundle\Data\Data;
use MusicBundle\Service\DownloadManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaFile
{
    private $id;

    private $name;

    private $path;

    private $previewPath;

    private $losslessPath;

    private $mediaItem;

    private $file;

    private $previewFile;

    private $losslessFile;

    private $createdAt;

    private $updatedAt;

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

    public function getPreviewPath()
    {
        return $this->previewPath;
    }

    public function getLosslessPath()
    {
        return $this->losslessPath;
    }

    public function setLosslessPath($losslessPath)
    {
        $this->losslessPath = $losslessPath;
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
        $this->setUpdatedAt(new \DateTime());
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getPreviewFile()
    {
        return $this->previewFile;
    }

    public function setPreviewFile($previewFile)
    {
        $this->previewFile = $previewFile;
    }

    public function getLosslessFile()
    {
        return $this->losslessFile;
    }

    public function setLosslessFile(UploadedFile $losslessFile = null)
    {
        $this->losslessFile = $losslessFile;
        $this->setUpdatedAt(new \DateTime());
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

    public function lifecycleFileUpload()
    {
        if ($this->getFile()) {
            $path = DownloadManager::createPath($this->getFile()->getClientOriginalName());

            $this->getFile()->move(
                Data::getUploadPath(),
                $path
            );

            $this->setPath($path);
            $this->setPreviewFile($this->getFile());
            $this->setFile(null);
        }

        if ($this->getLosslessFile()) {
            $path = DownloadManager::createPath($this->getLosslessFile()->getClientOriginalName());

            $this->getLosslessFile()->move(
                Data::getUploadPath(),
                $path
            );

            $this->setLosslessPath($path);
            $this->setLosslessFile(null);
        }
    }

    public function lifecycleFileDelete()
    {
        $path = Data::getUploadPath() . '/' . $this->getPath();

        if ($this->getPath() != null && file_exists($path)) {
            unlink($path);
        }

        $previewPath = Data::getUploadPath() . '/' . $this->getPreviewPath();

        if ($this->getPreviewPath() != null && file_exists($previewPath)) {
            unlink($previewPath);
        }

        $losslessPath = Data::getUploadPath() . '/' . $this->getLosslessPath();

        if ($this->getLosslessPath() != null && file_exists($losslessPath)) {
            unlink($losslessPath);
        }
    }
}