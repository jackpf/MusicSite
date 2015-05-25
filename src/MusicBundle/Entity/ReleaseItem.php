<?php

namespace MusicBundle\Entity;

class ReleaseItem extends MediaItem
{
    private $path;

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }
}