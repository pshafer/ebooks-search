<?php

namespace AppSecurityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/secure")
     */
    public function indexAction()
    {
        return $this->render('AppSecurityBundle:Default:index.html.twig');
    }
}
