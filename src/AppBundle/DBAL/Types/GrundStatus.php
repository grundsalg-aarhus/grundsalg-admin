<?php
namespace AppBundle\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

final class GrundStatus extends AbstractEnumType
{
  const AUKTION_SLUT = 'Auktion slut';
  const LEDIG = 'Ledig';
  const FREMTIDIG = 'Fremtidig';
  const ANNONCERET = 'Annonceret';
  const SOLGT = 'Solgt';
  const RESERVERET = 'Reserveret';
  const I_UDBUD = 'I udbud';

  protected static $choices = [
    self::AUKTION_SLUT => 'Auktion slut',
    self::LEDIG => 'Ledig',
    self::FREMTIDIG => 'Fremtidig',
    self::ANNONCERET => 'Annonceret',
    self::SOLGT => 'Solgt',
    self::RESERVERET => 'Reserveret',
    self::I_UDBUD => 'I udbud'
  ];
}