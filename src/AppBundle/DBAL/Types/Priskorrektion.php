<?php
namespace AppBundle\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

final class Priskorrektion extends AbstractEnumType
{
  const BELIGGENHED = 'Beliggenhed';
  const EKSTRA_FUNDERING = 'Ekstra fundering';
  const STOETTEMUR = 'Støttemur';
  const STOETTET_BYGGERI = 'Støttet byggeri';

  protected static $choices = [
    self::BELIGGENHED => 'Beliggenhed',
    self::EKSTRA_FUNDERING => 'Ekstra fundering',
    self::STOETTEMUR => 'Støttemur',
    self::STOETTET_BYGGERI => 'Støttet byggeri'
  ];
}
