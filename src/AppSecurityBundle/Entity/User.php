<?php
namespace AppSecurityBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppSecurityBundle\Entity\UserRepository")
 *
 */
class User implements UserInterface, EquatableInterface,\Serializable
{
  /**
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=30, unique=true)
   */
  private $username;

  /**
   * @ORM\Column(type="string")
   */
  private $password;

  /**
   * @ORM\Column(type="string", length=100)
   */
  private $firstName;

  /**
   * @ORM\Column(type="string", length=100)
   */
  private $lastName;

  /**
   * @ORM\Column(type="string", length=100, unique=true)
   */
  private $email;

  /**
   * @ORM\Column(name="is_active", type="boolean")
   */
  private $isActive;

  /**
   * @ORM\Column(type="string", length=100)
   */
  private $role;

  public function __construct()
  {
    $this->isActive = true;
    // may not be needed, see section on salt below
    // $this->salt = md5(uniqid(null, true));
  }

  public function getUsername()
  {
    return $this->username;
  }

  public function getSalt()
  {
    // you *may* need a real salt depending on your encoder
    // see section on salt below
    return null;
  }

  public function getPassword()
  {
    return $this->password;
  }

  public function getRoles()
  {
    return array($this->role);
  }

  public function getRole()
  {
    return $this->role;
  }

  public function setRole($role)
  {
    $this->role = $role;
  }

  public function eraseCredentials()
  {
  }

  /** @see \Serializable::serialize() */
  public function serialize()
  {
    return serialize(array(
      $this->id,
      $this->username,
      $this->password,
      // see section on salt below
      //$this->salt,
    ));
  }

  /** @see \Serializable::unserialize() */
  public function unserialize($serialized)
  {
    list (
      $this->id,
      $this->username,
      $this->password,
      // see section on salt below
      //$this->salt
      ) = unserialize($serialized);
  }

  /**
   *
   */
  public function isEqualTo(UserInterface $user) {
        return ($this->id == $user->getId() && $this->username === $user->getUsername());
  }

  /**
   * Get id
   *
   * @return integer
   */
  public function getId()
  {
      return $this->id;
  }

  /**
   * Set username
   *
   * @param string $username
   *
   * @return User
   */
  public function setUsername($username)
  {
      $this->username = $username;

      return $this;
  }

  /**
   * Set password
   *
   * @param string $password
   *
   * @return User
   */
  public function setPassword($password)
  {
      $this->password = $password;

      return $this;
  }


  /**
   * Set firstName
   *
   * @param string $firstName
   *
   * @return User
   */
  public function setFirstName($firstName)
  {
      $this->firstName = $firstName;

      return $this;
  }

  /**
   * Get firstName
   *
   * @return string
   */
  public function getFirstName()
  {
      return $this->firstName;
  }

  /**
   * Set lastName
   *
   * @param string $lastName
   *
   * @return User
   */
  public function setLastName($lastName)
  {
      $this->lastName = $lastName;

      return $this;
  }

  /**
   * Get lastName
   *
   * @return string
   */
  public function getLastName()
  {
      return $this->lastName;
  }

  /**
   * Set email
   *
   * @param string $email
   *
   * @return User
   */
  public function setEmail($email)
  {
      $this->email = $email;

      return $this;
  }

  /**
   * Get email
   *
   * @return string
   */
  public function getEmail()
  {
      return $this->email;
  }

  /**
   * Set isActive
   *
   * @param boolean $isActive
   *
   * @return User
   */
  public function setIsActive($isActive)
  {
      $this->isActive = $isActive;

      return $this;
  }

  /**
   * Get isActive
   *
   * @return boolean
   */
  public function getIsActive()
  {
      return $this->isActive;
  }
}
