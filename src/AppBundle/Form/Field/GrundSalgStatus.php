<?php

namespace AppBundle\Form\Field;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use AppBundle\DBAL\Types\GrundSalgStatus as SalgStatus;

class GrundSalgStatus extends AbstractType
{
  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'choices' => SalgStatus::getChoices()
      )
    );
  }

  public function getParent()
  {
    return ChoiceType::class;
  }
}
