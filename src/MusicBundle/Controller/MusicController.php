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

        $qb = $em->getRepository(sprintf('MusicBundle\Entity\%s', $repo))
            ->createQueryBuilder('i')
            ->select('i')
            ->orderBy('i.createdAt', 'desc');

        if ($request->query->has('search')) {
            $s = $qb->expr()->literal('%' . str_replace(' ', '%', $request->query->get('search')) . '%');
            $qb->andWhere($qb->expr()->like('i.title', $s));
        }

        $items = $this->get('knp_paginator')
            ->paginate($qb->getQuery(), $request->get('page', 1), 9);

        return $this->render('MusicBundle:Music:index.html.twig', [
            'items' => $items
        ]);
    }

    public function newsAction($slug)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $item = $em->getRepository('MusicBundle\Entity\NewsItem')
            ->findOneBySlug($slug);

        if (!$item) {
            throw $this->createNotFoundException('Item not found');
        }

        return $this->render('MusicBundle:Music:news_item.html.twig', [
            'item' => $item
        ]);
    }

    public function releaseAction($slug)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $item = $em->getRepository('MusicBundle\Entity\ReleaseItem')
            ->findOneBySlug($slug);

        if (!$item) {
            throw $this->createNotFoundException('Item not found');
        }

        return $this->render('MusicBundle:Music:release_item.html.twig', [
            'item' => $item
        ]);
    }

    public function mixAction($slug)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $item = $em->getRepository('MusicBundle\Entity\MixItem')
            ->findOneBySlug($slug);

        if (!$item) {
            throw $this->createNotFoundException('Item not found');
        }

        return $this->render('MusicBundle:Music:mix_item.html.twig', [
            'item' => $item
        ]);
    }
}
