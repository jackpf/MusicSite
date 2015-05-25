<?php

namespace MusicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MusicController extends Controller
{
    public function indexAction(Request $request, $type = null)
    {
        $em = $this->getDoctrine()->getEntityManager();

        if ($type == 'news') {
            $repo = 'NewsItem';
        } else if ($type == 'release') {
            $repo = 'ReleaseItem';
        } else if ($type == 'mix') {
            $repo = 'MixItem';
        } else {
            $repo = 'MediaItem';
        }

        $items = $em->getRepository(sprintf('MusicBundle\Entity\%s', $repo))
            ->createQueryBuilder('i')
            ->select('i')
            ->getQuery();
        $items = $this->get('knp_paginator')
            ->paginate($items, $request->get('page', 1), 6);

        return $this->render('MusicBundle:Music:index.html.twig', [
            'items' => $items
        ]);
    }

    public function newsAction($url)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $item = $em->getRepository('MusicBundle\Entity\NewsItem')
            ->findOneByUrl($url);

        if (!$item) {
            throw $this->createNotFoundException('Item not found');
        }

        return $this->render('MusicBundle:Music:news_item.html.twig', [
            'item' => $item
        ]);
    }

    public function releaseAction($url)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $item = $em->getRepository('MusicBundle\Entity\ReleaseItem')
            ->findOneByUrl($url);

        if (!$item) {
            throw $this->createNotFoundException('Item not found');
        }

        return $this->render('MusicBundle:Music:release_item.html.twig', [
            'item' => $item
        ]);
    }

    public function mixAction($url)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $item = $em->getRepository('MusicBundle\Entity\MixItem')
            ->findOneByUrl($url);

        if (!$item) {
            throw $this->createNotFoundException('Item not found');
        }

        return $this->render('MusicBundle:Music:mix_item.html.twig', [
            'item' => $item
        ]);
    }
}
