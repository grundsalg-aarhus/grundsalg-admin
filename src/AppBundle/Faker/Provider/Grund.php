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

class Grund extends Base
{
    public function type()
    {
        return GrundType::PARCELHUS;
    }

    public function salgsType()
    {
        return SalgsType::FASTPRIS;
    }

    public function maxetagem2()
    {
        return $this->generator->randomFloat($min = 0, $max = 9000);
    }

    public function areal()
    {
        return $this->generator->randomFloat($min = 0, $max = 500000);
    }

    public function arealvej()
    {
        return $this->generator->randomFloat($min = 0, $max = 4000);
    }

    public function arealkotelet()
    {
        return $this->generator->randomFloat($min = 0, $max = 11000);
    }

    public function bruttoareal()
    {
        return $this->generator->randomFloat($min = 0, $max = 11000);
    }

    public function prism2()
    {
        return $this->generator->randomFloat($min = 0, $max = 813000);
    }

    public function prisfoerkorrektion()
    {
        return $this->generator->randomFloat($min = 0, $max = 9706000);
    }

    public function korr1()
    {
        return $this->generator->randomFloat($min = -2003408, $max = 1679000);
    }

    public function pris()
    {
        return $this->generator->randomFloat($min = 0, $max = 135525000);
    }
}