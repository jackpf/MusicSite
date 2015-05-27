<?php

namespace Application\Sonata\AdminBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class KernelListener
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        /**
         * Having problems with inverse ID's being set by doctrine
         * could be to do with Sonata not flushing properly.
         * Setting inverse relations in the lifecycle event
         * and flushing here seems to fix it.
         * Not optimal but oh well.
         */
        if ($event->getRequest()->isMethod('POST')) {
            try {
                $this->em->flush();
            } catch (\Exception $e) {

            }
        }
    }
}