<?php

namespace EbooksBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class EBook
 * @ORM\Entity
 * @ORM\Table(name="ebook")
 */
class EBook {

  /**
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private $title;

  /**
   * @ORM\Column(type="text")
   */
  private $summary;

  /**
   * @ORM\Column(type="string", length=10, nullable=true)
   */
  private $isbn10;

  /**
   * @ORM\Column(type="string", length=13, nullable=true)
   */
  private $isbn13;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private $url;

  /**
   * @ORM\Column(type="string", length=255, nullable=true)
   */
  private $coverimg;

  /**
   * @ORM\ManyToMany(targetEntity="Subject", inversedBy="ebooks")
   * @ORM\JoinTable(name="ebooks_subjects")
   */
  private $subjects;

  /**
   * @ORM\ManyToOne(targetEntity="Vendor")
   */
  private $vendor;

  /**
   * @ORM\Column(type="json_array")
   */
  private $authors;
  /**
   * @ORM\Column(type="datetime")
   */
  private $created;

  /**
   * @ORM\Column(type="datetime")
   */
  private $updated;


    public function __construct() {

      $this->subjects = new ArrayCollection();
      $this->authors = array();
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
     * Set title
     *
     * @param string $title
     *
     * @return EBook
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set summary
     *
     * @param string $summary
     *
     * @return EBook
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary
     *
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set isbn10
     *
     * @param string $isbn10
     *
     * @return EBook
     */
    public function setIsbn10($isbn10)
    {
        $this->isbn10 = $isbn10;

        return $this;
    }

    /**
     * Get isbn10
     *
     * @return string
     */
    public function getIsbn10()
    {
        return $this->isbn10;
    }

    /**
     * Set isbn13
     *
     * @param string $isbn13
     *
     * @return EBook
     */
    public function setIsbn13($isbn13)
    {
        $this->isbn13 = $isbn13;

        return $this;
    }

    /**
     * Get isbn13
     *
     * @return string
     */
    public function getIsbn13()
    {
        return $this->isbn13;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return EBook
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set coverimg
     *
     * @param string $coverimg
     *
     * @return EBook
     */
    public function setCoverimg($coverimg)
    {
        $this->coverimg = $coverimg;

        return $this;
    }

    /**
     * Get coverimg
     *
     * @return string
     */
    public function getCoverimg()
    {
        return $this->coverimg;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return EBook
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
     * @return EBook
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

    public function getSubjects()
    {
      return $this->subjects;
    }

    public function setSubjects($subjects)
    {
      $this->subjects = $subjects;
    }

    public function addSubject(Subject $subject)
    {
      $this->subjects[] = $subject;
    }

    /**
     * @return mixed
     */
    public function getVendor() {
      return $this->vendor;
    }

    /**
     * @param mixed $vendor
     */
    public function setVendor($vendor) {
      $this->vendor = $vendor;
    }



    public function getAuthors()
    {
      return $this->authors;
    }

    public function setAuthors($authors)
    {
      $this->authors = $authors;
    }
}
