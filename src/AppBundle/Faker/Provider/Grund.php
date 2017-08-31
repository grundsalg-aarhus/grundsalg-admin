<?php
/**
 * Created by PhpStorm.
 * User: turegjorup
 * Date: 31/07/2017
 * Time: 12.47
 */

namespace AppBundle\Faker\Provider;

use AppBundle\DBAL\Types\GrundType;
use AppBundle\DBAL\Types\SalgsType;
use Faker\Provider\Base;

class Grund extends Base {
  public function type()
  {
    return GrundType::PARCELHUS;
  }

  public function salgsType() {
    return SalgsType::FASTPRIS;
  }
}