<?php

namespace Application\Sonata\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends Controller
{
    public function ordersAction(Request $request)
    {
        $user = $this->get('security.context')->getToken()->getUser();

        $criteria = ['user' => $user];
        if (!$request->query->get('incomplete')) {
            $criteria['status'] = 'authorized';
        }

        $orders = $this->getDoctrine()->getEntityManager()
            ->getRepository('MusicBundle\Entity\Order')
            ->findBy($criteria, ['createdAt' => 'desc']);

        $orders = $this->get('knp_paginator')
            ->paginate($orders, $request->get('page', 1), 10);

        return $this->render('FOSUserBundle:Profile:orders.html.twig',[
            'orders' => $orders,
        ]);
    }

    public function favouritesAction(Request $request, $title = null)
    {
        $favourites = $this->get('knp_paginator')->paginate(
            $this->get('security.context')
                ->getToken()->getUser()
                ->getFavourites(),
            $request->get('page', 1), 10
        );

        return $this->render('MusicBundle:Music:index.html.twig',[
            'items' => $favourites,
            'title' => $title,
        ]);
    }
}
