<?php

namespace AppBundle\Form\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use AppBundle\DBAL\Types\GrundType as Type;

class GrundType extends AbstractType
{
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'choices' => Type::getChoices()
      )
    );
  }

  public function getParent()
  {
    return ChoiceType::class;
  }
}
