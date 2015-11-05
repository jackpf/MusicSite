<?php
namespace MusicBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Payum\Bundle\PayumBundle\Controller\NotifyController as BaseController;

class NotifyController extends BaseController
{
    public function doUnsafeAction(Request $request)
    {
        parent::doUnsafeAction($request);

        return new Response('', 200); // Return 200 instead of payum's 204
    }

    public function doAction(Request $request)
    {
        parent::doAction($request);

        return new Response('', 200); // Return 200 instead of payum's 204
    }
}