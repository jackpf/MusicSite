<?php

namespace MusicBundle\EventListener;

use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Kernel;

class KernelListener
{
    private $kernel;

    private $router;

    private $logger;

    public function __construct(Kernel $kernel, Router $router, Logger $logger)
    {
        $this->kernel = $kernel;
        $this->router = $router;
        $this->logger = $logger;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if ($this->kernel->getEnvironment() != 'dev') {
            $exception = $event->getException();

            if (!($exception instanceof HttpException)) {
                $this->logger->error($exception->getMessage());
            }

            $event->setResponse(
                new RedirectResponse($this->router->generate('music_error', ['code' => $event->getException() instanceof HttpException ? $exception->getStatusCode() : 500]))
            );
        }
    }
}