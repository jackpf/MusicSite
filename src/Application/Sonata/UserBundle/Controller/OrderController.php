<?php

namespace Application\Sonata\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OrderController extends Controller
{
    public function ordersAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $orders = $this->getDoctrine()->getEntityManager()
            ->getRepository('MusicBundle\Entity\Order')
            ->findByUser($user);

        return $this->render('FOSUserBundle:Profile:orders.html.twig',[
            'orders' => $orders,
        ]);
    }
}
