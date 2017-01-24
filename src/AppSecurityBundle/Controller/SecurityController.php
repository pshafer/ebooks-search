<?php

namespace AppSecurityBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller
{
  /**
   * @Route("/login", name="security_login_form")
   *
   */
  public function loginAction()
  {
    $user = $this->getUser();
    if ($user instanceof UserInterface) {
      print '<pre>' . print_r($user,true) . '</pre>';
      die();
      return $this->redirectToRoute('homepage');
    }

    $helper = $this->get('security.authentication_utils');

    return $this->render('AppSecurityBundle:security:login.html.twig', array(
      // last username entered by user (if any)
      'last_username' => $helper->getLastUsername(),
      //last authentication error (if any)
      'error' => $helper->getLastAuthenticationError(),
    ));
  }


  /**
   * @Route("/login/local", name="security_login_check")
   *
   */
  public function loginCheckAction()
  {

  }

  /**
   * @Route("/login/cas", name="security_login_cas")
   */
  public function loginCasAction()
  {

  }
}