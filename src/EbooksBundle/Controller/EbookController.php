<?php

namespace EbooksBundle\Controller;

use EbooksBundle\Entity\EBook;
use EbooksBundle\Entity\Subject;
use EbooksBundle\Entity\Vendor;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use EbooksBundle\Form\EbookType;
use Symfony\Component\HttpFoundation\Response;

class EbookController extends Controller
{
  private $tabs;

  public function __construct() {
    $tabs = array();
    $tabs[0] = array(
      'title'   => 'E-Books List',
      'path'    => 'admin_ebook_list',
      'icon'    => 'fa-plus',
      'active'  => true,
    );

    $tabs[1] = array(
      'title'   => 'Add E-book',
      'path'    => 'admin_ebook_new',
      'icon'    => 'fa-plus',
      'active'  => 'false'
    );

    $tabs[2] = array(
      'title'   => 'Import E-Books',
      'path'    => '',
      'icon'    => 'fa-upload',
      'active'  => 'false'
    );
  }

  /**
   * @Route("/admin/ebooks", name="admin_ebook_list")
   * @Security("has_role('ROLE_EBOOK_ADMIN')")
   */
  public function indexAction()
  {
    $repository = $this->getDoctrine()->getRepository('EbooksBundle:EBook');
    $ebooks = $repository->findAll();

    return $this->render('EbooksBundle:Ebook:list.html.twig', array(
      'ebooks' => $ebooks
    ));

    return $this->render('EbooksBundle:Ebook:list.html.twig');
  }

  /**
   * @Route("/admin/ebook/new", name="admin_ebook_new")
   * @Security("has_role('ROLE_EBOOK_ADMIN')")
   */
  public function newAction(Request $request)
  {

    $form = $this->createForm(EbookType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $ebook = $form->getData();

      $date = new \DateTime("now");
      $ebook->setCreated($date);
      $ebook->setUpdated($date);


      $em = $this->getDoctrine()->getManager();
      $em->persist($ebook);
      $em->flush();

      $this->addFlash('success', 'Ebook was created!');

      return $this->redirectToRoute('admin_ebook_list');
    }

    return $this->render('EbooksBundle:Ebook:form.html.twig', array(
      'form' => $form->createView(),
    ));
  }

  /**
   * @Route("/admin/ebook/{id}/edit", name="admin_ebook_edit")
   * @Security("has_role('ROLE_EBOOK_ADMIN')")
   *
   */
  public function editAction(Request $request, EBook $ebook)
  {
    $form = $this->createForm(EbookType::class, $ebook);

    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid())
    {
      $ebook = $form->getData();
      $date = new \DateTime("now");
      $ebook->setUpdated($date);

      $em = $this->getDoctrine()->getManager();
      $em->persist($ebook);
      $em->flush();

      $this->addFlash('success', 'EBook' . $ebook->getTitle() . ' Updated!');

      $this->indexItem($ebook);

      return $this->redirectToRoute('admin_ebook_list');
    }

    return $this->render('EbooksBundle:Ebook:form.html.twig', array(
      'form' => $form->createView(),
    ));
  }


  private function indexItem(Ebook $book)
  {
    $client = $this->get('solarium.client');

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
    $doc->vendor = $vendor->getName();

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

    $this->addflash('success', 'Index: ' . $result->getStatus());
    $this->addFlash('success', 'Index: ' . $result->getQueryTime());

  }

}