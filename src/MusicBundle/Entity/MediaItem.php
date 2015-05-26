<?php

namespace MusicBundle\Entity;

use MusicBundle\Data\Data;

class MediaItem
{
    protected $id;

    protected $title;

    protected $shortContent;

    protected $content;

    protected $image;

    private $imageFile;

    protected $background;

    private $backgroundFile;

    protected $slug;

    protected $createdAt;

    protected $updatedAt;

    protected $deletedAt;

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getShortContent()
    {
        return $this->shortContent;
    }

    public function setShortContent($shortContent)
    {
        $this->shortContent = $shortContent;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImageFile($imageFile)
    {
        $this->setUpdatedAt(new \DateTime());
        $this->imageFile = $imageFile;
    }

    public function getBackground()
    {
        return $this->background;
    }

    public function setBackground($background)
    {
        $this->background = $background;
    }

    public function getBackgroundFile()
    {
        return $this->backgroundFile;
    }

    public function setBackgroundFile($backgroundFile)
    {
        $this->setUpdatedAt(new \DateTime());
        $this->backgroundFile = $backgroundFile;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
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

    public function getDeletedAt()
    {
        return $this->deletdAt;
    }

    public function setDeletedAt($deletedAt)
    {
        $this->deletdAt = $deletedAt;
    }

    public function lifecycleFileUpload()
    {
        if ($this->getImageFile()) {
            $original = $this->getImageFile()->getClientOriginalName();
            $path = sha1($original + rand()) . '.' . @end(explode('.', $original));

            $this->getImageFile()->move(Data::$UPLOAD_PATH, $path);
            $this->setImage($path);
            $this->setImageFile(null);
        }

        if ($this->getBackgroundFile()) {
            $original = $this->getBackgroundFile()->getClientOriginalName();
            $path = sha1($original + rand()) . '.' . @end(explode('.', $original));

            $this->getBackgroundFile()->move(Data::$UPLOAD_PATH, $path);
            $this->setBackground($path);
            $this->setBackgroundFile(null);
        }
    }

    public function lifecycleFileDelete()
    {
        $imagePath = Data::$UPLOAD_PATH . '/' . $this->getImage();

        if ($this->getImage() != null && file_exists($imagePath)) {
            unlink($imagePath);
        }

        $backgroundPath = Data::$UPLOAD_PATH . '/' . $this->getBackground();

        if ($this->getBackground() != null && file_exists($backgroundPath)) {
            unlink($backgroundPath);
        }
    }
}