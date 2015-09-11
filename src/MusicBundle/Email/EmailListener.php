<?php

namespace MusicBundle\Email;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\GenericEvent;

class EmailListener
{
    const ORDER_EVENT = 'event.order';

    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function orderEvent(GenericEvent $event)
    {
        $order = $event->getArgument('order');

        $this->mailer->send($order->getUser()->getEmail(), 'MusicBundle:Email:order.html.twig', ['order' => $order]);
    }
}