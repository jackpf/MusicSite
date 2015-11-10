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
        if ($this->getLosslessFile()) {
            $path = DownloadManager::createPath($this->getLosslessFile()->getClientOriginalName());

            $this->getLosslessFile()->move(
                Data::getUploadPath(),
                $path
            );

            $this->delete($this->getLosslessPath());
            $this->delete($this->getPath());
            $this->delete($this->getPreviewPath());

            $this->setLosslessPath($path);
            $this->setFile($this->getLosslessFile()); // Handled later
            $this->setLosslessFile(null);
        }
    }

    public function lifecycleFileDelete()
    {
        $this->delete($this->getLosslessPath());
        $this->delete($this->getPath());
        $this->delete($this->getPreviewPath());
    }

    private function delete($filename)
    {
        $path = Data::getUploadPath() . '/' . $filename;

        if ($filename != null && file_exists($path)) {
            unlink($path);
        }
    }
}