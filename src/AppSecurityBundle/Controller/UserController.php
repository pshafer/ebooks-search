<?php

namespace AppSecurityBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UserController extends Controller
{
  /**
   * @Route("/user/dashboard", name="user_dashboard")
   * @Security("has_role('ROLE_USER')")
   *
   */
  public function userdashboardAction() {
    return $this->render('AppSecurityBundle:user:user-dashboard.html.twig');
  }

  /**
   * @Route("/admin/user", name="user_admin_list")
   * @Security("has_role('ROLE_ADMIN')")
   */
  public function useradminAction()
  {

    return $this->render('AppSecurityBundle:user:user-admin.html.twig');
  }
}

