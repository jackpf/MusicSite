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

    private $file;

    protected $slug;

    protected $createdAt;

    protected $updatedAt;

    protected $author;

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

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file)
    {
        $this->setUpdatedAt(new \DateTime());
        $this->file = $file;
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

    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
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

        $this->setImage($path);
        $this->setFile(null);
    }
}