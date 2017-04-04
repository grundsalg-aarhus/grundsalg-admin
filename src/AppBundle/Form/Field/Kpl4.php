<?php

namespace AppBundle\Form\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use AppBundle\DBAL\Types\Kpl4 as Kpl4Type;

class Kpl4 extends AbstractType
{
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'choices' => Kpl4Type::getChoices()
      )
    );
  }

  public function getParent()
  {
    return ChoiceType::class;
  }
}
