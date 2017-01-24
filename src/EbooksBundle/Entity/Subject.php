<?php

namespace EbooksBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Subject
 * @package EbooksBundle\Entity
 *
 * @ORM\Entity(repositoryClass="EbooksBundle\Repository\SubjectRepository")
 * @ORM\Table(name="subject")
 *
 */
class Subject {

  /**
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private $name;

  /**
   * @ORM\ManyToMany(targetEntity="EBook", mappedBy="subjects")
   */
  private $ebooks;

  /**
   * @ORM\Column(type="datetime")
   */
  private $created;

  /**
   * @ORM\Column(type="datetime")
   */
  private $updated;


    public function __construct() {
      $this->ebooks = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Subject
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Subject
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return Subject
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }
}
