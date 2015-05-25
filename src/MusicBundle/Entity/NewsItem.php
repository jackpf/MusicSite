<?php

namespace MusicBundle\Entity;

class NewsItem extends MediaItem
{
    protected $author;

    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }
}