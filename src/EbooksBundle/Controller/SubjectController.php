<?php

namespace EbooksBundle\Controller;

use EbooksBundle\Entity\EBook;
use EbooksBundle\Entity\Subject;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use EbooksBundle\Form\SubjectType;

class SubjectController extends Controller
{
  /**
   * @Route("/admin/subjects", name="admin_subject_list")
   * @Security("has_role('ROLE_EBOOK_ADMIN')")
   */
  public function indexAction()
  {
    $repository = $this->getDoctrine()->getRepository('EbooksBundle:Subject');
    $subjects = $repository->findAllOrderedByName();

    return $this->render('EbooksBundle:Subject:admin-list.html.twig', array(
      'subjects' => $subjects
    ));

  }

  /**
   * @Route("/admin/subject/new", name="admin_subject_new")
   * @Security("has_role('ROLE_EBOOK_ADMIN')")
   *
   *
   */
  public function newAction(Request $request)
  {
    $form = $this->createForm(SubjectType::class);

    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()) {

      $subject = $form->getData();

      $date = new \DateTime("now");
      $subject->setCreated($date);
      $subject->setUpdated($date);

      $em = $this->getDoctrine()->getManager();
      $em->persist($subject);
      $em->flush();

      $this->addFlash('success', 'Subject was created!');

      return $this->redirectToRoute('admin_subject_list');

    }

    return $this->render('EbooksBundle:Subject:subject-form.html.twig', array(
      'form' => $form->createView(),
    ));

  }

  /**
   * @Route("/admin/subject/{id}/edit", name="admin_subject_edit")
   * @Security("has_role('ROLE_EBOOK_ADMIN')")
   */
  public function editAction(Request $request, Subject $subject)
  {
    $form = $this->createForm(SubjectType::class, $subject);

    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid())
    {
      $subject = $form->getData();
      $date = new \DateTime("now");
      $subject->setUpdated($date);

      $em = $this->getDoctrine()->getManager();
      $em->persist($subject);
      $em->flush();

      $this->addFlash('success', 'Subject' . $subject->getName() . ' Updated!');

      return $this->redirectToRoute('admin_subject_list');
    }

    return $this->render('EbooksBundle:Subject:subject-form.html.twig', array(
      'form' => $form->createView(),
    ));
  }
}