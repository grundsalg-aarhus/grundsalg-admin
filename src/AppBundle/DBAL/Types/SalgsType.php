<?php
namespace AppBundle\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

final class SalgsType extends AbstractEnumType
{
  const AUKTION = 'Auktion';
  const ETGM2 = 'Etgm2';
  const FASTPRIS = 'Fastpris';
  const KVADRATMETERPRIS = 'Kvadratmeterpris';

  protected static $choices = [
    self::AUKTION => 'Auktion',
    self::ETGM2 => 'Etgm2',
    self::FASTPRIS => 'Fastpris',
    self::KVADRATMETERPRIS => 'Kvadratmeterpris'
  ];
}