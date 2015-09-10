<?php

namespace Application\Sonata\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class OrderController extends Controller
{
    public function ordersAction(Request $request)
    {
        $user = $this->get('security.context')->getToken()->getUser();

        $orders = $this->getDoctrine()->getEntityManager()
            ->getRepository('MusicBundle\Entity\Order')
            ->findByUser($user, ['createdAt' => 'desc']);

        $orders = $this->get('knp_paginator')
            ->paginate($orders, $request->get('page', 1), 10);

        return $this->render('FOSUserBundle:Profile:orders.html.twig',[
            'orders' => $orders,
        ]);
    }
}
