<?php

namespace EbooksBundle\Form;

use EbooksBundle\Entity\Vendor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VendorType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('name', null, array(
      'attr' => array(
        'placeholder' => 'Add Vendor Name'
      )
    ))
      ->add('save', SubmitType::class, array('label' => 'Save'));
  }

  public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefaults([
      'data_class' => 'EbooksBundle\Entity\Vendor',
    ]);
  }
}