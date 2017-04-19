<?php
namespace AppBundle\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

final class GrundSalgStatus extends AbstractEnumType
{
  const ACCEPTERET = 'Accepteret';
  const AUKTION_ANNULLERET = 'Auktion annulleret';
  const AUKTION_IGANG = 'Auktion igang';
  const AUKTION_SLUT = 'Auktion slut';
  const LEDIG = 'Ledig';
  const RESERVATION_ANNULLERET = 'Reservation annulleret';
  const RESERVERET = 'Reserveret';
  const SOLGT = 'Solgt';
  const SKOEDE_ANNULLERET = 'Skøde annulleret';
  const SKOEDE_REKVIRERET = 'Skøde rekvireret';
  const TILBUD_ANNULLERET = 'Tilbud annulleret';
  const TILBUD_SENDT = 'Tilbud sendt';

  protected static $choices = [
    self::ACCEPTERET => 'Accepteret',
    self::AUKTION_ANNULLERET => 'Auktion annulleret',
    self::AUKTION_IGANG => 'Auktion igang',
    self::AUKTION_SLUT => 'Auktion slut',
    self::LEDIG => 'Ledig',
    self::RESERVATION_ANNULLERET => 'Reservation annulleret',
    self::RESERVERET => 'Reserveret',
    self::SKOEDE_ANNULLERET => 'Skøde annulleret',
    self::SKOEDE_REKVIRERET => 'Skøde rekvireret',
    self::SOLGT => 'Solgt',
    self::TILBUD_SENDT => 'Tilbud sendt',
    self::TILBUD_ANNULLERET => 'Tilbud annulleret',

  ];
}