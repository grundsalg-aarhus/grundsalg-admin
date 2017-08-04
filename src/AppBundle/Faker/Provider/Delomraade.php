<?php
/**
 * Created by PhpStorm.
 * User: turegjorup
 * Date: 31/07/2017
 * Time: 12.47
 */

namespace AppBundle\Faker\Provider;

use AppBundle\DBAL\Types\Anvendelse;
use AppBundle\DBAL\Types\GrundType;
use AppBundle\DBAL\Types\Kpl4;
use AppBundle\DBAL\Types\SalgsType;
use Faker\Provider\Base;

class Delomraade extends Base {
  public function anvendelse()
  {
    return Anvendelse::AABEN_LAV_BEBYGGELSE;
  }

  public function kpl4() {
    return Kpl4::BL;
  }
}