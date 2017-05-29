<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Place;
use JavierEguiluz\Bundle\EasyAdminBundle\Form\Type\EasyAdminAutocompleteType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 */
class OpkoebType extends AbstractType {

  /**
   * @param FormBuilderInterface $builder
   * @param array $options
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {

    $builder->add('matrik1', null, array('label' => 'Mat1'));
    $builder->add('matrik2', null, array('label' => 'Mat2'));
    $builder->add('ejerlav', null, array('label' => 'Ejerlav'));
    $builder->add('m2', null, array('label' => 'Areal'));
    $builder->add('opkoebDato', DateType::class, array('label' => 'Opkøbt d.', 'widget' => 'single_text'));
    $builder->add('pris', null, array('label' => 'Pris'));
    $builder->add('procentaflp', null, array('label' => '% af LP', 'disabled' => TRUE));
    $builder->add('bemaerkning', TextType::class, array('label' => 'Bemærkning', 'required' => false));

  }

  /**
   * @param OptionsResolverInterface $resolver
   */
  public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefaults([
      'data_class' => 'AppBundle\Entity\Opkoeb'
    ]);
  }

  /**
   * @return string
   */
  public function getName() {
    return 'appbundle_opkoeb';
  }

}
