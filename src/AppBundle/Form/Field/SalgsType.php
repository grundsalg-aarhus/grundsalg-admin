<?php

namespace AppBundle\Form\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SalgsType extends AbstractType
{
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'choices' => \AppBundle\DBAL\Types\SalgsType::getChoices()
      )
    );
  }

  public function getParent()
  {
    return ChoiceType::class;
  }
}
