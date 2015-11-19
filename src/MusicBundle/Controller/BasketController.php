<?php

namespace MusicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\RedirectResponse;

class BasketController extends Controller
{
    public function addAction($id)
    {
        $basket = $this->get('music.basket');
        $variant = $this->getDoctrine()->getManager()
            ->getRepository('MusicBundle:ReleaseVariant')
            ->find($id);

        if (!$variant) {
            throw $this->createNotFoundException('Variant not found');
        }

        if (!$basket->contains($variant)) {
            $basket->add($variant);
        }

        $basket->store();

        return new RedirectResponse($this->get('music.twig.helper_extension')->itemPath($variant->getMediaItem()));
    }
}
