<?php
/**
 * Created by PhpStorm.
 * User: turegjorup
 * Date: 01/09/2017
 * Time: 13.35
 */

namespace Tests\AppBundle\Service;

use AppBundle\Controller\ApiController;
use AppBundle\DBAL\Types\GrundPublicStatus;
use AppBundle\DBAL\Types\GrundSalgStatus;
use AppBundle\DBAL\Types\GrundStatus;
use AppBundle\Entity\Grund;
use AppBundle\Service\GrundsalgBankHolidayService;
use AppBundle\Service\GrundsalgPublicPropertiesService;
use PHPUnit\Framework\TestCase;

class GrundsalgPublicPropertiesServiceTest extends TestCase
{

    /**
     * GRUND-255:
     *
     * Accepteret = solgt
     * fremtidig / Ledig = Fremtidig
     * Ledig/Ledig = Ledig
     * Solgt / Ledig = ledig
     * Ledig/ reserveret = Reserveret
     * Ledig/ skøde rekvireret = solgt
     * Auktion slut / Skøde rekvireret = Solgt
     * Ledig / Solgt = Solgt
     * Auktion slut / Solgt = Solgt
     * annonceret / ledig = i udbud.
     *
     * @param $status
     * @param $salgstatus
     * @param $publicStatus
     *
     * @dataProvider providerTestGetPublicStatus
     */
    public function testGetPublicStatus($status, $salgstatus, $publicStatus)
    {

        $grund   = new Grund();
        $service = new GrundsalgPublicPropertiesService(new GrundsalgBankHolidayService());

        $grund->setStatus($status);
        $grund->setSalgstatus($salgstatus);

        $public = $service->getPublicStatus($grund);

        $this->assertEquals($publicStatus, $public);

    }

    /**
     * GRUND-255:
     *
     * Accepteret = solgt
     * fremtidig / Ledig = Fremtidig
     * Ledig/Ledig = Ledig
     * Solgt / Ledig = ledig
     * Ledig/ reserveret = Reserveret
     * Ledig/ skøde rekvireret = solgt
     * Auktion slut / Skøde rekvireret = Solgt
     * Ledig / Solgt = Solgt
     * Auktion slut / Solgt = Solgt
     * annonceret / ledig = i udbud.
     *
     * @return array
     */
    public function providerTestGetPublicStatus()
    {

        // status | salgStatus = webstatus
        return [
            [GrundStatus::FREMTIDIG, GrundSalgStatus::ACCEPTERET, GrundPublicStatus::SOLGT],
            [GrundStatus::FREMTIDIG, GrundSalgStatus::LEDIG, GrundPublicStatus::FREMTIDIG],
            [GrundStatus::LEDIG, GrundSalgStatus::LEDIG, GrundPublicStatus::LEDIG],
            [GrundStatus::SOLGT, GrundSalgStatus::LEDIG, GrundPublicStatus::LEDIG],
            [GrundStatus::LEDIG, GrundSalgStatus::RESERVERET, GrundPublicStatus::RESERVERET],
            [GrundStatus::LEDIG, GrundSalgStatus::SKOEDE_REKVIRERET, GrundPublicStatus::SOLGT],
            [GrundStatus::AUKTION_SLUT, GrundSalgStatus::SKOEDE_REKVIRERET, GrundPublicStatus::SOLGT],
            [GrundStatus::LEDIG, GrundSalgStatus::SOLGT, GrundPublicStatus::SOLGT],
            [GrundStatus::AUKTION_SLUT, GrundSalgStatus::SOLGT, GrundPublicStatus::SOLGT],
            [GrundStatus::ANNONCERET, GrundSalgStatus::LEDIG, GrundPublicStatus::I_UDBUD],
        ];
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method     = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

}
