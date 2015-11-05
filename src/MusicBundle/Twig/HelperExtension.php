<?php

namespace MusicBundle\Twig;

use MusicBundle\Entity\MediaItem;
use MusicBundle\Entity\MixItem;
use MusicBundle\Entity\NewsItem;
use MusicBundle\Entity\ReleaseItem;
use Symfony\Component\Routing\Router;

class HelperExtension extends \Twig_Extension
{
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('item_path', array($this, 'itemPath')),
            new \Twig_SimpleFunction('upload_path', array($this, 'uploadPath')),
            new \Twig_SimpleFunction('version', array($this, 'version')),
        );
    }

    public function itemPath(MediaItem $item)
    {
        if ($item instanceof NewsItem) {
            $path = 'music_news_item';
        } else if ($item instanceof ReleaseItem) {
            $path = 'music_release_item';
        } else if ($item instanceof MixItem) {
            $path = 'music_mix_item';
        } else {
            return '';
        }

        return $this->router->generate($path, ['slug' => $item->getSlug()]);
    }

    public function uploadPath($path)
    {
        return '/uploads/' . $path;
    }

    public function version()
    {
        return substr(exec('git rev-parse HEAD'), 0, 7);
    }

    public function getName()
    {
        return 'helper_extension';
    }
}