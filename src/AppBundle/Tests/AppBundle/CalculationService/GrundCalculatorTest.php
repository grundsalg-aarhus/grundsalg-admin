<?php
/**
 * Created by PhpStorm.
 * User: turegjorup
 * Date: 01/09/2017
 * Time: 13.35
 */

namespace Tests\AppBundle\Service;

use AppBundle\CalculationService\GrundCalculator;
use AppBundle\Controller\ApiController;
use AppBundle\DBAL\Types\GrundPublicStatus;
use AppBundle\DBAL\Types\GrundSalgStatus;
use AppBundle\DBAL\Types\GrundStatus;
use AppBundle\DBAL\Types\GrundType;
use AppBundle\DBAL\Types\SalgsType;
use AppBundle\Entity\Grund;
use AppBundle\Service\GrundsalgBankHolidayService;
use AppBundle\Service\GrundsalgPublicPropertiesService;
use PHPUnit\Framework\TestCase;

class GrundCalculatorTest extends TestCase
{
    public function testCalculateStatus()
    {
        $calculator = new GrundCalculator();
        $grund = new Grund();

        $now = new \DateTime();
        $tomorrow = clone $now;
        $tomorrow->add(new \DateInterval('P1D'));
        $first = clone $now;
        $first->sub(new \DateInterval('P100D'));
        $end = clone $now;
        $end->add(new \DateInterval('P10D'));

        $grund->setType(GrundType::ERHVERV);
        $grund->setStatus(GrundStatus::LEDIG);
        $grund->setSalgstatus(GrundSalgStatus::LEDIG);

        $grund->setDatoannonce($now);
        $grund->setDatoannonce1($first);

        $grund->setSalgstype(SalgsType::AUKTION);
        $grund->setAuktionstartdato($now);
        $grund->setAuktionslutdato($end);

        $this->invokeMethod($calculator, 'calculateStatus', [$grund, false, ['annonceres' => []]]);
        $this->assertEquals(GrundStatus::ANNONCERET, $grund->getStatus());

        $grund->setDatoannonce($tomorrow);
        $grund->setDatoannonce1($first);
        $grund->setAuktionstartdato($tomorrow);
        $grund->setAuktionslutdato($end);

        $grund->setStatus(GrundStatus::LEDIG);
        $grund->setSalgstatus(GrundSalgStatus::LEDIG);

        $this->invokeMethod($calculator, 'calculateStatus', [$grund, false, ['annonceres' => []]]);
        $this->assertEquals(GrundStatus::FREMTIDIG, $grund->getStatus());

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
