<?php

namespace AppSecurityBundle\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\InMemoryUserProvider;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

use phpCAS;


class CasAuthenticator extends AbstractGuardAuthenticator
{
  /**
   * @var \Symfony\Component\Routing\RouterInterface
   */
  private $router;

  private $cas_debug;
  private $cas_debug_file;
  private $cas_host;
  private $cas_port;
  private $cas_context;
  private $cas_cert;
  private $cas_version;


  /**
   * Default message for authentication failure.
   *
   * @var string
   */
  private $failMessage = 'Invalid credentials';

  /**
   * Creates a new instance of FormAuthenticator
   */
  public function __construct($config) {

    $this->cas_debug        = $config['cas_auth']['cas_debug'];
    $this->cas_debug_file   = $config['cas_auth']['cas_debug_file'];
    $this->cas_host         = $config['cas_auth']['cas_server_host'];
    $this->cas_port         = $config['cas_auth']['cas_server_port'];
    $this->cas_context      = $config['cas_auth']['cas_server_uri'];

    switch($config['cas_auth']['cas_server_version'])
    {
      case "3.0":
        $this->cas_version = "3.0";
        break;
      case "2.0":
        $this->cas_version = "2.0";
        break;
      case "saml_1_1":
        $this->cas_version = "S1";
        break;
      case "1.0":
      default:
        $this->cas_version = "1.0";
        break;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getCredentials(Request $request)
  {
    if ($request->getPathInfo() != '/login/cas' || !$request->isMethod('GET')) {
      return;
    }

    phpCAS::client($this->cas_version, $this->cas_host, $this->cas_port, $this->cas_context);

    if(phpCAS::isAuthenticated()) {
      $userAttributes = phpCAS::getAttributes();

    }else{
      phpCAS::forceAuthentication();
    }


    return array(
      'username' => 'admin',
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getUser($credentials, UserProviderInterface $userProvider)
  {
    if(isset($credentials['username']))
    {
      try{
        return $userProvider->loadUserByUsername($credentials['username']);
      } catch(UsernameNotFoundException $e) {
        throw new CustomUserMessageAuthenticationException($this->failMessage);
      }
    }

    throw new CustomUserMessageAuthenticationException($this->failMessage);
  }

  /**
   * {@inheritdoc}
   */
  public function checkCredentials($credentials, UserInterface $user)
  {
    return true;
  }

  /**
   * {@inheritdoc}
   */
  public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
  {
    $url = $this->router->generate('homepage');
    return new RedirectResponse($url);
  }

  /**
   * {@inheritdoc}
   */
  public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
  {
    $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
    $url = $this->router->generate('security_login_form');
    return new RedirectResponse($url);
  }

  /**
   * {@inheritdoc}
   */
  public function start(Request $request, AuthenticationException $authException = null)
  {
    $url = $this->router->generate('security_login_cas');
    return new RedirectResponse($url);
  }

  /**
   * {@inheritdoc}
   */
  public function supportsRememberMe()
  {
    return false;
  }

}