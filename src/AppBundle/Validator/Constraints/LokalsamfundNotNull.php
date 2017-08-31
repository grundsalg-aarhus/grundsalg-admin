<?php
/**
 * Created by PhpStorm.
 * User: turegjorup
 * Date: 17/08/2017
 * Time: 09.45
 */

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/** @Annotation */
class LokalsamfundNotNull extends Constraint {
  public $message = 'Det valgte Salgsområde har ikke noget Lokalplan/Lokalsamfund tilknyttet.';

  public function getTargets() {
    return self::CLASS_CONSTRAINT;
  }
}
