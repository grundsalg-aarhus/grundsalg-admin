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

class Lokalplan extends Base {
  public function nr()
  {
    return $this->generator->numberBetween($min = 100, $max = 900);
  }
}