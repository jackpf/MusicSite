<?php

namespace MusicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MusicController extends Controller
{
    public function indexAction()
    {
        return $this->render('MusicBundle:Music:index.html.twig');
    }
}
