<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Grund;

/**
 *
 */
class MultiGrundEmbedType extends AbstractType {

  /**
   * @param FormBuilderInterface $builder
   * @param array $options
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {

    $builder->add('mnr', null, array('label' => 'app.grund.mnr'));
    $builder->add('mnr2', null, array('label' => 'app.grund.mnr2'));
    $builder->add('delareal', null, array('label' => 'app.grund.delareal'));
    $builder->add('husnummer', null, array('label' => 'app.grund.husnummer'));
    $builder->add('bogstav', null, array('label' => 'app.grund.bogstav'));
    $builder->add('maxetagem2', null, array('label' => 'app.grund.maxetagem2'));
    $builder->add('areal', null, array('label' => 'app.grund.areal'));
    $builder->add('arealvej', null, array('label' => 'app.grund.arealvej'));
    $builder->add('arealkotelet', null, array('label' => 'app.grund.arealkotelet'));
    $builder->add('fastpris', null, array('label' => 'app.grund.fastpris'));

  }

  /**
   * @param OptionsResolverInterface $resolver
   */
  public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefaults([
      'data_class' => 'AppBundle\Entity\Grund'
    ]);
  }

  /**
   * @return string
   */
  public function getName() {
    return 'appbundle_multi_grund';
  }

}
