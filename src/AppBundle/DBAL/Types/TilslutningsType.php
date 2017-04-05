<?php
namespace AppBundle\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

final class TilslutningsType extends AbstractEnumType
{
  const KLOAK = 'Kloak';
  const EL = 'El';
  const VAND = 'Vand';
  const FJERNVARME = 'Fjernvarme';
  const INGEN_TILSLUTNINGSBIDRAG = 'Ingen tilslutningsbidrag';

  protected static $choices = [
    self::KLOAK => 'Kloak',
    self::EL => 'El',
    self::VAND => 'Vand',
    self::FJERNVARME => 'Fjernvarme',
    self::INGEN_TILSLUTNINGSBIDRAG => 'Ingen tilslutningsbidrag'
  ];
}