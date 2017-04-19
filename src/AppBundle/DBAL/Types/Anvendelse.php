<?php
namespace AppBundle\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

final class Anvendelse extends AbstractEnumType
{
  const AABEN_LAV_BEBYGGELSE = 'Åben-lav bebyggelse';
  const ERHVERV = 'Erhverv';
  const ETAGEBEBYGGELSE = 'Etagebebyggelse';
  const INSTITUTION = 'Institution';
  const TAET_LAV_BEBYGGELSE = 'Tæt-lav bebyggelse';

  protected static $choices = [
    self::AABEN_LAV_BEBYGGELSE => 'Åben-lav bebyggelse',
    self::ERHVERV => 'Erhverv',
    self::ETAGEBEBYGGELSE => 'Etagebebyggelse',
    self::INSTITUTION => 'Institution',
    self::TAET_LAV_BEBYGGELSE => 'Tæt-lav bebyggelse'
  ];
}