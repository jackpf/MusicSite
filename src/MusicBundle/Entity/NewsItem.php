<?php

namespace MusicBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class NewsItem extends MediaItem
{
    private $author;

    private $categories;

    public function __construct()
    {
        parent::__construct();

        $this->categories = new ArrayCollection();
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }

    public function setCategories(ArrayCollection $categories = null)
    {
        $this->categories = $categories;
    }

    public function addCategory(NewsCategory $category)
    {
        $this->categories->set($category->getId(), $category);
    }

    public function removeCategory(NewsCategory $category)
    {
        $this->categories->removeElement($category);
    }

    public function getCategories()
    {
        return $this->categories;
    }
}