<?php

namespace MusicBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class NewsCategory
{
    private $id;

    private $title;

    private $slug;

    private $description;

    private $newsItems;

    public function __construct()
    {
        $this->newsItems = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setNewsItems(ArrayCollection $newsItems = null)
    {
        $this->newsItems = $newsItems;
    }

    public function addNewsItem(NewsItem $newsItem)
    {
        $this->newsItems->set($newsItem->getId(), $newsItem);
    }

    public function removeNewsItem(NewsItem $newsItem)
    {
        $this->newsItems->removeElement($newsItem);
    }

    public function getNewsItems()
    {
        return $this->newsItem;
    }

    public function __toString()
    {
        return $this->getTitle();
    }
}