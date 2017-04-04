<?php
namespace AppBundle\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

final class GrundSalgStatus extends AbstractEnumType
{
  const SOLGT = 'Solgt';
  const LEDIG = 'Ledig';
  const SKOEDE_REKVIRERET = 'Skøde rekvireret';
  const RESERVERET = 'Reserveret';
  const TILBUD_SENDT = 'Tilbud sendt';
  const AUKTION_IGANG = 'Auktion igang';
  const AUKTION_SLUT = 'Auktion slut';
  const ACCEPTERET = 'Accepteret';


  protected static $choices = [
    self::SOLGT => 'Solgt',
    self::LEDIG => 'Ledig',
    self::SKOEDE_REKVIRERET => 'Skøde rekvireret',
    self::RESERVERET => 'Reserveret',
    self::TILBUD_SENDT => 'Tilbud sendt',
    self::AUKTION_IGANG => 'Auktion igang',
    self::AUKTION_SLUT => 'Auktion slut',
    self::ACCEPTERET => 'Accepteret'
  ];
}