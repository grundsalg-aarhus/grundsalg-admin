<?php
namespace AppBundle\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

final class Kpl4 extends AbstractEnumType
{
  const CENTER = 'Center';
  const BL = 'BL';
  const BO = 'BO';
  const ER = 'ER';
  const JO = 'JO';
  const OF = 'OF';
  const RE = 'RE';

  protected static $choices = [
    self::CENTER => 'Center',
    self::BL => 'BL',
    self::BO => 'BO',
    self::ER => 'ER',
    self::JO => 'JO',
    self::OF => 'OF',
    self::RE => 'RE'
  ];
}