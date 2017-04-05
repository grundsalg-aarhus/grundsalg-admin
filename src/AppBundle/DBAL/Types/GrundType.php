<?php
namespace AppBundle\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

final class GrundType extends AbstractEnumType
{
  const PARCELHUS = 'Parcelhusgrund';
  const ERHVERV = 'Erhvervsgrund';
  const STORPARCEL = 'Storparcel';
  const ANDRE = 'Andre';
  const OFF_FORMAAL = 'Off. formål';
  const CENTER = 'Centergrund';

  protected static $choices = [
    self::PARCELHUS => 'Parcelhusgrund',
    self::ERHVERV => 'Erhvervsgrund',
    self::STORPARCEL => 'Storparcel',
    self::ANDRE => 'Andre',
    self::OFF_FORMAAL => 'Off. formål',
    self::CENTER => 'Centergrund'
  ];
}