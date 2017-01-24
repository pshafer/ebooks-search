<?php

namespace EbooksBundle\Controller;

use EbooksBundle\Entity\EBook;
use EbooksBundle\Entity\Vendor;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use EbooksBundle\Form\VendorType;


class VendorController extends Controller
{
  /**
   * @Route("/admin/vendors", name="admin_vendor_list")
   * @Security("has_role('ROLE_EBOOK_ADMIN')")
   */
  public function indexAction()
  {
    $repository = $this->getDoctrine()->getRepository('EbooksBundle:Vendor');
    $vendors = $repository->findAllOrderedByName();

    return $this->render('EbooksBundle:Vendor:admin-list.html.twig', array(
      'vendors' => $vendors
    ));
  }

  /**
   * @Route("/admin/vendor/new", name="add_new_vendor")
   * @Security("has_role('ROLE_EBOOK_ADMIN')")
   *
   */
  public function newAction(Request $request)
  {
    $form = $this->createForm(VendorType::class);

    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()) {

      $vendor = $form->getData();

      $date = new \DateTime("now");
      $vendor->setCreatedAt($date);
      $vendor->setUpdatedAt($date);

      $em = $this->getDoctrine()->getManager();
      $em->persist($vendor);
      $em->flush();

      $this->addFlash('success', 'Vendor was created!');

      return $this->redirectToRoute('admin_vendor_list');

    }

    return $this->render('EbooksBundle:Vendor:vendor-form.html.twig', array(
      'form' => $form->createView(),
    ));
  }

  /**
   * @Route("/admin/vendor/{id}/edit", name="edit_vendor")
   * @Security("has_role('ROLE_EBOOK_ADMIN')")
   */
  public function editAction(Request $request, Vendor $vendor)
  {
    $form = $this->createForm(VendorType::class, $vendor);

    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid())
    {
      $vendor = $form->getData();
      $date = new \DateTime("now");
      $vendor->setUpdatedAt($date);

      $em = $this->getDoctrine()->getManager();
      $em->persist($vendor);
      $em->flush();

      $this->addFlash('success', 'Vendor' . $vendor->getName() . ' Updated!');

      return $this->redirectToRoute('admin_vendor_list');
    }

    return $this->render('EbooksBundle:Vendor:vendor-form.html.twig', array(
      'form' => $form->createView(),
    ));
  }
}