<?php
namespace AppBundle\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

final class GrundPublicStatus extends AbstractEnumType
{
  const SOLGT = 'Solgt';
  const FREMTIDIG = 'Fremtidig';
  const LEDIG = 'Ledig';
  const RESERVERET = 'Reserveret';
  const I_UDBUD = 'I udbud';

  protected static $choices = [
    self::SOLGT => 'Solgt',
    self::FREMTIDIG => 'Fremtidig',
    self::LEDIG => 'Ledig',
    self::RESERVERET => 'Reserveret',
    self::I_UDBUD => 'I udbud'
  ];
}