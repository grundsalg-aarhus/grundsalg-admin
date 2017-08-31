<?php
/**
 * Created by PhpStorm.
 * User: turegjorup
 * Date: 17/08/2017
 * Time: 09.45
 */

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/** @Annotation */
class LokalsamfundNotNullValidator extends ConstraintValidator {
  public function validate($grundcollection, Constraint $constraint) {
    $salgsomraade = $grundcollection->getSalgsomraade();
    $lokalsamfund = $salgsomraade->getLokalplan() ? $salgsomraade->getLokalplan()->getLokalsamfund() : NULL;

    if (!$lokalsamfund) {
      $this->context->buildViolation($constraint->message)
        ->atPath('salgsomraade')
        ->addViolation();
    }
  }
}
