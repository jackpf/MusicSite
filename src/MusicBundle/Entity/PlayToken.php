<?php

namespace MusicBundle\Entity;

class PlayToken
{
    private $id;

    private $mediaFile;

    private $token;

    private $createdAt;

    public function __construct(MediaFile $mediaFile)
    {
        $this->token = sha1($mediaFile->getId() + microtime() + rand());
        $this->mediaFile = $mediaFile;
        $this->createdAt = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getMediaFile()
    {
        return $this->mediaFile;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getCreatedAt()
    {
        return $this->expired;
    }
}