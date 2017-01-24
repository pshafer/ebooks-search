<?php

namespace EbooksBundle\Form;

use EbooksBundle\Entity\Subject;
use EbooksBundle\Entity\Vendor;
use EbooksBundle\Repository\SubjectRepository;
use EbooksBundle\Repository\VendorRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EbookType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('title')
      ->add('isbn10')
      ->add('isbn13')
      ->add('summary', TextareaType::class)
      ->add('url', UrlType::class)
      ->add('authors', CollectionType::class, array(
        'allow_add' => true,
        'allow_delete' => true,
        'entry_type' => TextType::class,
        'entry_options' => array(
          'attr' => array('class' => 'author-field')
        ),
      ))
      ->add('subjects', EntityType::class, array(
        'class' => 'EbooksBundle:Subject',
        'query_builder' => function(SubjectRepository $srepo) {
          return $srepo->createAlpabeticalQueryBuilder();
        },
        'choice_label' => function($subject) {
          return $subject->getName();
        },
        'multiple' => true,
        'placeholder' => 'Select Subjects',
        'attr' => array('class' => 'chzn-select'),
      ))
      ->add('vendor', EntityType::class, array(
        'class' => 'EbooksBundle\Entity\Vendor',
        'query_builder' => function(VendorRepository $vrepo) {
          return $vrepo->createAlpabeticalQueryBuilder();
        },
        'choice_label' => function($vendor) {
          return $vendor->getName();
        },
        'multiple' => false,
        'placeholder' => 'Select Vendor',
        'attr' => array('class' => 'chzn-select'),
      ))
      ->add('save', SubmitType::class);
  }

  public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefaults([
      'data_class' => 'EbooksBundle\Entity\EBook',
    ]);
  }
}