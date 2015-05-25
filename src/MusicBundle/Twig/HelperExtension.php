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

        return $this->router->generate($path, ['url' => $item->getUrl()]);
    }

    public function getName()
    {
        return 'helper_extension';
    }
}