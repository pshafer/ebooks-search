<?php

namespace EbooksBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/old",name="old_home")
     */
    public function indexAction()
    {
        return $this->render('searchresults.html.twig');
    }
}
