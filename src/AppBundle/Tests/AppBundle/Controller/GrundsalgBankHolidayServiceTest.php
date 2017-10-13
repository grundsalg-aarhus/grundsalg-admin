<?php
/**
 * Created by PhpStorm.
 * User: turegjorup
 * Date: 01/09/2017
 * Time: 13.35
 */

namespace Tests\AppBundle\Controller;

use AppBundle\Controller\ApiController;
use AppBundle\DBAL\Types\GrundPublicStatus;
use AppBundle\DBAL\Types\GrundSalgStatus;
use AppBundle\DBAL\Types\GrundStatus;
use AppBundle\DBAL\Types\GrundType;
use AppBundle\Entity\Grund;
use AppBundle\Service\GrundsalgBankHolidayService;
use AppBundle\Service\GrundsalgPublicStatusService;
use PHPUnit\Framework\TestCase;

class GrundsalgBankHolidayServiceTest extends TestCase
{

    public function testIsWaitingPeriod()
    {
        $service = new GrundsalgBankHolidayService();
        $grund = new Grund();
        $date = new \DateTime();
        $now = new \DateTime();

        $grund->setType(GrundType::PARCELHUS);

        $date->setDate(2017, 10, 10);
        $now->setDate(2017, 10, 13);
        $grund->setAccept($date);
        $this->assertTrue($service->isWaitingPeriod($grund, 6, $now));

        $date->setDate(2017, 10, 10);
        $now->setDate(2017, 10, 19);
        $grund->setAccept($date);
        $this->assertFalse($service->isWaitingPeriod($grund, 6, $now));

        $date->setDate(2018, 3, 23);
        $now->setDate(2018, 4, 6);
        $grund->setAccept($date);
        $this->assertFalse($service->isWaitingPeriod($grund, 6, $now));

        $grund->setType(GrundType::STORPARCEL);

        $date->setDate(2017, 10, 10);
        $now->setDate(2017, 10, 13);
        $grund->setAccept($date);
        $this->assertFalse($service->isWaitingPeriod($grund, 6, $now));

    }

    public function testGetWaitingPeriodEndDay()
    {
        $service = new GrundsalgBankHolidayService();
        $grund = new Grund();
        $date = new \DateTime();

        $grund->setType(GrundType::PARCELHUS);

        $date->setDate(2017, 10, 10);
        $grund->setAccept($date);
        $endTime = $this->invokeMethod($service, 'getWaitingPeriodEndDay', [$grund]);
        $this->assertEquals('2017-10-18T23:59:59', $endTime->format('Y-m-d\TH:i:s'));

        $date->setDate(2017, 12, 18);
        $grund->setAccept($date);
        $endTime = $this->invokeMethod($service, 'getWaitingPeriodEndDay', [$grund]);
        $this->assertEquals('2017-12-28T23:59:59', $endTime->format('Y-m-d\TH:i:s'));

        $date->setDate(2018, 3, 23);
        $grund->setAccept($date);
        $endTime = $this->invokeMethod($service, 'getWaitingPeriodEndDay', [$grund]);
        $this->assertEquals('2018-04-05T23:59:59', $endTime->format('Y-m-d\TH:i:s'));

        $date->setDate(2018, 5, 7);
        $grund->setAccept($date);
        $endTime = $this->invokeMethod($service, 'getWaitingPeriodEndDay', [$grund]);
        $this->assertEquals('2018-05-16T23:59:59', $endTime->format('Y-m-d\TH:i:s'));

        $grund->setType(GrundType::STORPARCEL);

        $date->setDate(2018, 5, 7);
        $grund->setAccept($date);
        $endTime = $this->invokeMethod($service, 'getWaitingPeriodEndDay', [$grund]);
        $this->assertEquals('2018-05-07T23:59:59', $endTime->format('Y-m-d\TH:i:s'));
    }

    /**
     *
     */
    public function testIsWeekDay()
    {
        $service = new GrundsalgBankHolidayService();
        $date = new \DateTime();

        $date->setDate(2017, 10, 10);
        $this->assertTrue($this->invokeMethod($service, 'isWeekDay', [$date]));

        $date->setDate(2017, 3, 10);
        $this->assertTrue($this->invokeMethod($service, 'isWeekDay', [$date]));

        $date->setDate(2017, 8, 1);
        $this->assertTrue($this->invokeMethod($service, 'isWeekDay', [$date]));

        $date->setDate(2017, 10, 22);
        $this->assertFalse($this->invokeMethod($service, 'isWeekDay', [$date]));

        $date->setDate(2018, 5, 10);
        $this->assertFalse($this->invokeMethod($service, 'isWeekDay', [$date]));
    }

    /**
     * Test for easter related bank holidays
     */
    public function testIsChangingBankHoliday()
    {
        $service = new GrundsalgBankHolidayService();
        $date = new \DateTime();

        // Random non holidays
        $date->setDate(2017, 10, 10);
        $this->assertFalse($this->invokeMethod($service, 'isChangingBankHoliday', [$date]));

        $date->setDate(2017, 3, 10);
        $this->assertFalse($this->invokeMethod($service, 'isChangingBankHoliday', [$date]));

        $date->setDate(2017, 8, 25);
        $this->assertFalse($this->invokeMethod($service, 'isChangingBankHoliday', [$date]));


        // Palmesøndag
        $date->setDate(2017, 4, 9);
        $this->assertTrue($this->invokeMethod($service, 'isChangingBankHoliday', [$date]));
        $date->setDate(2018, 3, 25);
        $this->assertTrue($this->invokeMethod($service, 'isChangingBankHoliday', [$date]));
        $date->setDate(2019, 4, 14);
        $this->assertTrue($this->invokeMethod($service, 'isChangingBankHoliday', [$date]));

        // Skærtorsdag
        $date->setDate(2017, 4, 13);
        $this->assertTrue($this->invokeMethod($service, 'isChangingBankHoliday', [$date]));
        $date->setDate(2018, 3, 29);
        $this->assertTrue($this->invokeMethod($service, 'isChangingBankHoliday', [$date]));
        $date->setDate(2019, 4, 18);
        $this->assertTrue($this->invokeMethod($service, 'isChangingBankHoliday', [$date]));

        // Langfredag
        $date->setDate(2017, 4, 14);
        $this->assertTrue($this->invokeMethod($service, 'isChangingBankHoliday', [$date]));
        $date->setDate(2018, 3, 30);
        $this->assertTrue($this->invokeMethod($service, 'isChangingBankHoliday', [$date]));
        $date->setDate(2019, 4, 19);
        $this->assertTrue($this->invokeMethod($service, 'isChangingBankHoliday', [$date]));

        // Påskedag
        $date->setDate(2017, 4, 16);
        $this->assertTrue($this->invokeMethod($service, 'isChangingBankHoliday', [$date]));
        $date->setDate(2018, 4, 1);
        $this->assertTrue($this->invokeMethod($service, 'isChangingBankHoliday', [$date]));
        $date->setDate(2019, 4, 21);
        $this->assertTrue($this->invokeMethod($service, 'isChangingBankHoliday', [$date]));

        // 2. Påskedag
        $date->setDate(2017, 4, 17);
        $this->assertTrue($this->invokeMethod($service, 'isChangingBankHoliday', [$date]));
        $date->setDate(2018, 4, 2);
        $this->assertTrue($this->invokeMethod($service, 'isChangingBankHoliday', [$date]));
        $date->setDate(2019, 4, 22);
        $this->assertTrue($this->invokeMethod($service, 'isChangingBankHoliday', [$date]));

        // Store Bededag
        $date->setDate(2017, 5, 12);
        $this->assertTrue($this->invokeMethod($service, 'isChangingBankHoliday', [$date]));
        $date->setDate(2018, 4, 27);
        $this->assertTrue($this->invokeMethod($service, 'isChangingBankHoliday', [$date]));
        $date->setDate(2019, 5, 17);
        $this->assertTrue($this->invokeMethod($service, 'isChangingBankHoliday', [$date]));

        // Kr. Himmelfart
        $date->setDate(2017, 5, 25);
        $this->assertTrue($this->invokeMethod($service, 'isChangingBankHoliday', [$date]));
        $date->setDate(2018, 5, 10);
        $this->assertTrue($this->invokeMethod($service, 'isChangingBankHoliday', [$date]));
        $date->setDate(2019, 5, 30);
        $this->assertTrue($this->invokeMethod($service, 'isChangingBankHoliday', [$date]));

        // Pinsedag
        $date->setDate(2017, 6, 4);
        $this->assertTrue($this->invokeMethod($service, 'isChangingBankHoliday', [$date]));
        $date->setDate(2018, 5, 20);
        $this->assertTrue($this->invokeMethod($service, 'isChangingBankHoliday', [$date]));
        $date->setDate(2019, 6, 9);
        $this->assertTrue($this->invokeMethod($service, 'isChangingBankHoliday', [$date]));

        // 2. Pinsedag
        $date->setDate(2017, 6, 5);
        $this->assertTrue($this->invokeMethod($service, 'isChangingBankHoliday', [$date]));
        $date->setDate(2018, 5, 21);
        $this->assertTrue($this->invokeMethod($service, 'isChangingBankHoliday', [$date]));
        $date->setDate(2019, 6, 10);
        $this->assertTrue($this->invokeMethod($service, 'isChangingBankHoliday', [$date]));

        //------- TESTING FAKE 2017-10-19 -------//
        $date->setDate(2017, 10, 19);
        $this->assertTrue($this->invokeMethod($service, 'isChangingBankHoliday', [$date]));

    }

    /**
     * Test for bank holidays on fixed dates
     */
    public function testIsFixedBankHoliday()
    {
        $service = new GrundsalgBankHolidayService();

        $date = new \DateTime();

        // Random non holidays
        $date->setDate(2017, 10, 10);
        $this->assertFalse($this->invokeMethod($service, 'isFixedBankHoliday', [$date]));

        $date->setDate(2017, 3, 10);
        $this->assertFalse($this->invokeMethod($service, 'isFixedBankHoliday', [$date]));

        $date->setDate(2017, 8, 25);
        $this->assertFalse($this->invokeMethod($service, 'isFixedBankHoliday', [$date]));



        // Grundlovsdag
        $date->setDate(2017, 6, 5);
        $this->assertTrue($this->invokeMethod($service, 'isFixedBankHoliday', [$date]));

        // Xmas
        $date->setDate(2017, 12, 24);
        $this->assertTrue($this->invokeMethod($service, 'isFixedBankHoliday', [$date]));

        $date->setDate(2017, 12, 25);
        $this->assertTrue($this->invokeMethod($service, 'isFixedBankHoliday', [$date]));

        $date->setDate(2017, 12, 26);
        $this->assertTrue($this->invokeMethod($service, 'isFixedBankHoliday', [$date]));

        // New Years Day
        $date->setDate(2017, 12, 31);
        $this->assertTrue($this->invokeMethod($service, 'isFixedBankHoliday', [$date]));

        $date->setDate(2017, 1, 1);
        $this->assertTrue($this->invokeMethod($service, 'isFixedBankHoliday', [$date]));

        //------- TESTING FAKE 2017-10-20 -------//
        $date->setDate(2017, 10, 20);
        $this->assertTrue($this->invokeMethod($service, 'isFixedBankHoliday', [$date]));
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
