<?php

namespace EbooksBundle\Command;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\DBALException;
use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

use EbooksBundle\Entity\EBook;
use EbooksBundle\Entity\Subject;
use EbooksBundle\Entity\Vendor;

class ImportEbooksCommand extends ContainerAwareCommand
{
  private $doctrine;

  private $csv;
  private $fileName;
  private $ignoreFirstList = true;

  protected function configure()
  {
    $this->setName('ebooks:import')
      ->setDescription("Imports ebooks from a CSV")
      ->setHelp("This command allows you to import ebooks from a CSV file")
      ->addOption(
        'file',
        null,
        InputOption::VALUE_REQUIRED,
        'Please provide input file...',
        ''
      )
      ->addOption(
        'header',
        null,
        InputOption::VALUE_OPTIONAL,
        'User Header Field Names',
        'yes'
      );
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $this->doctrine = $this->getContainer()->get('doctrine');

    $this->csv = array();
    $this->ignoreFirstList = (strtolower($input->getOption('header')) === 'yes' ) ? true : false;
    $this->fileName = $input->getOption('file');
    $fs = new Filesystem();

    if($fs->exists($this->fileName))
    {
      $this->parseCSV();
      $this->processCSV();
    }else{
      $output->write($fileName . " does not exist!");
    }
  }

  private function parseCSV()
  {
    $header = null;
    if(($handle = fopen($this->fileName, 'r')) !== FALSE)
    {
      while(($line = fgetcsv($handle)) !== FALSE) {
        if($header === null){
          $header = $line;
          continue;
        }
        $this->csv[] = array_combine($header, $line);
      }
    }
  }

  private function processCSV()
  {

    $em = $this->doctrine->getManager();

    $bookRepo = $this->doctrine->getRepository('EbooksBundle:EBook');

    foreach($this->csv as $item)
    {


      $book = new EBook();

      $date = new \DateTime("now");
      $book->setCreated($date);
      $book->setUpdated($date);
      $book->setTitle(trim($item['title']));
      $book->setSummary(trim($item['title']));

      if(!empty($item['isbn'])){
        $book->setIsbn10(trim($item['isbn']));
      }

      if(!empty($item['isbn13'])) {
        $book->setIsbn13(trim($item['isbn13']));
      }

      $book->setUrl(trim($item['field_link_url']));

      if(!empty($item['subjects'])) {
        $subjectNames = explode('/', $item['subjects']);
        $subjects = $this->getSubjects($subjectNames);

        $book->setSubjects($subjects);
      }

      if(!empty($item['authors'])){
        $authorNames = explode('/', $item['authors']);
        $authors = $this->getAuthors($authorNames);

        $book->setAuthors($authors);
      }

      if(!empty($item['vendor'])){
        $vendor = $this->getVendor($item['vendor']);
        $book->setVendor($vendor);
      }

      try{
        $em->persist($book);
        $em->flush();
      }catch(DBALException $e){
        die($e);
      }
    }
  }


  private function getSubjects($subjectNames)
  {
    $em = $this->doctrine->getManager();
    $subjectRepo = $this->doctrine->getRepository('EbooksBundle:Subject');

    $subjects = new ArrayCollection();

    foreach($subjectNames as $name)
    {
      $name = trim($name);

      $subject = $subjectRepo->findOneByName($name);


      if(is_null($subject)){

        $subject = new Subject();
        $subject->setName($name);
        $date = new \DateTime("now");
        $subject->setCreated($date);
        $subject->setUpdated($date);

        try{
          $em->persist($subject);
          $em->flush();
        }catch(DBALException $e) {
          die($e);
        }

      }
      if(!$subjects->contains($subject)) {
        $subjects->add($subject);
      }

    }

    return $subjects;

  }

  private function getAuthors($authorNames)
  {
    $authors = array();
    foreach($authorNames as $name)
    {
      if(!empty($name)){
        $authors[] = $name;
      }
    }

    return $authors;
  }

  private function getVendor($vendorName)
  {
    $em = $this->doctrine->getManager();
    $vendorRepo = $this->doctrine->getRepository('EbooksBundle:Vendor');

    $vendorName = trim($vendorName);

    $vendor = $vendorRepo->findOneByName($vendorName);
    if(is_null($vendor))
    {
      $vendor = new Vendor();
      $vendor->setName($vendorName);
      $date = new \DateTime("now");
      $vendor->setCreatedAt($date);
      $vendor->setUpdatedAt($date);

      try{
        $em->persist($vendor);
        $em->flush();
      }catch(DBALException $e) {
        die($e);
      }
    }

    return $vendor;
  }
}