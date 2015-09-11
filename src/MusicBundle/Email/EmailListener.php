<?php

namespace MusicBundle\Email;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\GenericEvent;

class EmailListener
{
    const ORDER_EVENT = 'event.order';

    private $mailer;

    private $mailerUser;

    public function __construct(Mailer $mailer, $mailerUser)
    {
        $this->mailer = $mailer;
        $this->mailerUser = $mailerUser;
    }

    public function orderEvent(GenericEvent $event)
    {
        $order = $event->getArgument('order');

        $this->mailer->send($order->getUser()->getEmail(), 'MusicBundle:Email:order.html.twig', ['order' => $order]);
        $this->mailer->send($this->mailerUser, 'MusicBundle:Email:order_admin.html.twig', ['order' => $order]);
    }
}