<?php

namespace MusicBundle\Controller;

use MusicBundle\Data\Data;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller
{
    public function newsCategoriesFragmentAction()
    {
        $categories = $this->getDoctrine()->getManager()
            ->getRepository('MusicBundle:NewsCategory')
            ->findAll();

        return $this->render('MusicBundle:Music:_news_categories.html.twig', ['categories' => $categories]);
    }

    public function indexAction(Request $request, $repo = null)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $qb = $em->getRepository(sprintf('MusicBundle:%s', $repo))
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

    public function newsCategoryAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $category = $em->getRepository('MusicBundle:NewsCategory')
            ->findBySlug($slug);

        if (!$category) {
            throw $this->createNotFoundException('Category not found');
        }

        $qb = $em->getRepository('MusicBundle:NewsItem')
            ->createQueryBuilder('i')
            ->select('i')
            ->where(':category member of i.categories')
            ->setParameter('category', $category)
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

    public function itemAction($slug, $repo, $tpl)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $tokenManager = $this->get('music.token_manager');

        $item = $em->getRepository(sprintf('MusicBundle:%s', $repo))
            ->findOneBySlug($slug);

        if (!$item) {
            throw $this->createNotFoundException('Item not found');
        }

        $tokens = $tokenManager->createTokens($item);

        return $this->render(sprintf('MusicBundle:Music:%s.html.twig', $tpl), [
            'item' => $item,
            'tokens' => $tokens,
        ]);
    }

    public function aboutAction()
    {
        return $this->render('MusicBundle:Music:about.html.twig');
    }
}
