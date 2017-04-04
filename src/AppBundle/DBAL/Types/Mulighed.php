<?php
namespace AppBundle\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

final class Mulighed extends AbstractEnumType
{
  const INSTITUTION = 'Institution';
  const DETAILHANDEL = 'Detailhandel';
  const BOLIG = 'Bolig';
  const DAGLIGVAREBUTIK = 'Dagligvarebutik';

  protected static $choices = [
    self::INSTITUTION => 'Institution',
    self::DETAILHANDEL => 'Detailhandel',
    self::BOLIG => 'Bolig',
    self::DAGLIGVAREBUTIK => 'Dagligvarebutik'
  ];
}