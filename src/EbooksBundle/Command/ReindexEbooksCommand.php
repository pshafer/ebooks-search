<?php
/**
 * Created by PhpStorm.
 * User: shafer
 * Date: 9/22/16
 * Time: 2:58 PM
 */

namespace EbooksBundle\Command;

use EbooksBundle\Repository\EBookRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;

use EbooksBundle\Entity\EBook;
use EbooksBundle\Entity\Subject;
use EbooksBundle\Entity\Vendor;


class ReindexEbooksCommand extends ContainerAwareCommand
{

  protected function configure()
  {
    $this->setName('ebooks:index')
      ->setDescription("Peforms full reindex of ebook data")
      ->setHelp("Provides the ability to perofrm a full re-index of ebook data");
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $doctrine = $this->getContainer()->get('doctrine');
    $em = $doctrine->getManager();
    $repo = $em->getRepository('EbooksBundle:EBook');

    $books = $repo->findAll();
    $units = 100;
    $limit = floor(count($books) / 100);

    $progress = new ProgressBar($output, $units);

    $progress->start();

    $count = 0;
    foreach($books as $book)
    {
      $count++;
      $this->indexBook($book);

      if($count > $limit){
        $count = 0;
        $progress->advance();
      }
    }

    $progress->finish();
  }

  private function indexBook(EBook $book)
  {
    $client = $this->getContainer()->get('solarium.client');

    $update = $client->createUpdate();

    $doc = $update->createDocument();

    $doc->id = $book->getId();
    $doc->title = $book->getTitle();
    $doc->url = $book->getUrl();
    $doc->isbn = $book->getIsbn10();
    $doc->isbn13 = $book->getIsbn13();
    $doc->summary = $book->getSummary();

    $doc->author = $book->getAuthors();


    $vendor = $book->getVendor();
    if(!is_null($vendor)){
      $doc->vendor = $vendor->getName();
    }



    $subjectArray = array();
    $subjects = $book->getSubjects();

    foreach($subjects->toArray() as $subject)
    {
      $subjectArray[] = $subject->getName();
    }

    if(count($subjectArray)){
      $doc->subject = $subjectArray;
    }

    $update->addDocuments(array($doc));
    $update->addCommit();

    $result = $client->update($update);
  }
}