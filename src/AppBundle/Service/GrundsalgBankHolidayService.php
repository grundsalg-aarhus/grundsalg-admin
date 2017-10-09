<?php
/**
 * @file
 * Contains the GrundsalgCommunicationService which handles synchronizations from
 * this system to the Grundsalg presentations web page.
 */

namespace AppBundle\Service;

use AppBundle\DBAL\Types\GrundType;
use AppBundle\Entity\Salgsomraade;
use Faker\Provider\DateTime;
use GuzzleHttp\Client;
use AppBundle\Entity\Grund;

/**
 * Class GrundsalgHolidayService
 *
 * @package AppBundle
 */
class GrundsalgBankHolidayService
{

    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * @param Grund $grund
     * @param int $numberOfDays
     * @param DateTime|null $dateTime
     *
     * @return bool
     */
    public function isWaitingPeriod(Grund $grund, int $numberOfDays = 6, \DateTime $dateTime = null)
    {
        $dateTime = empty($dateTime) ? new \DateTime() : $dateTime;
        $endDay   = $this->getWaitingPeriodEndDay($grund, $numberOfDays);

        return $endDay > $dateTime;
    }

    /**
     * @param Grund $grund
     * @param int $numberOfDays
     *
     * @return \DateTime|null
     */
    public function getWaitingPeriodEndDay(Grund $grund, $numberOfDays = 6)
    {
        if ( ! $grund->getAccept()) {
            return null;
        }

        if ($grund->getType() === GrundType::PARCELHUS) {
            $endDay = $grund->getAccept();
            $count  = 0;

            while ($count < $numberOfDays) {
                $endDay->add(new \DateInterval('P1D'));
                if ($this->isWeekDay($endDay)) {
                    $count++;
                }
            }
        } else {
            $endDay = $grund->getAccept();
        }

        $endDay->setTime(23, 59, 59);

        return $endDay;
    }

    /**
     * Check if given date is neither saturday, sunday or a bank holiday
     *
     * @param \DateTime $dateTime
     *
     * @return bool
     */
    private function isWeekDay(\DateTime $dateTime)
    {
        if ($dateTime->format('N') > 5 || $this->isChangingBankHoliday($dateTime) || $this->isFixedBankHoliday($dateTime)) {
            return false;
        }

        return true;
    }

    /**
     * Check if a given day is a bank holiday that falls on the same date each year.
     *
     * Checks for 'Nytårsdag', 'Grundlovsdag', 'Juleaften', 'Juledag', '2. Juledag', 'Nytårsaften'
     *
     * @param \DateTime $dateTime
     *
     * @return bool
     */
    private function isFixedBankHoliday(\DateTime $dateTime)
    {
        $date = $dateTime->format('m-d');

        switch ($date) {
            case '01-01': // Nytårsdag
                return true;
            case '06-05': // Grundlovsdag
                return true;
            case '12-24': // Juleaften
                return true;
            case '12-25': // Juledag
                return true;
            case '12-26': // 2. Juledag
                return true;
            case '12-31': // Nytårsaften
                return true;
            default:
                return false;
        }
    }

    /**
     * Check if a given day is a bank holiday where the date is defined by easter
     *
     * Checks for Palmesøndag, Skærtorsdag, Langfredag, Påskedag, 2. Påskedag, Store bededag,
     * Kristi Himmelfartsdag, Pinsedag, 2. Pinsedag
     *
     * @param \DateTime $dateTime
     *
     * @return bool
     */
    private function isChangingBankHoliday(\DateTime $dateTime)
    {
        $day          = $dateTime->format('m-d');
        $year         = intval($dateTime->format('Y'));
        $easterOffset = easter_days($year);

        $easter = new \DateTime();
        $easter->setDate($year, 3, 21);
        $easter->add(new \DateInterval('P'.$easterOffset.'D'));

        $palmSunday = clone $easter;
        $palmSunday->sub(new \DateInterval('P7D'));

        $maundyThursday = clone $easter;
        $maundyThursday->sub(new \DateInterval('P3D'));

        $goodFriday = clone $easter;
        $goodFriday->sub(new \DateInterval('P2D'));

        $easterMonday = clone $easter;
        $easterMonday->add(new \DateInterval('P1D'));

        $commonPrayerDay = clone $easter;
        $commonPrayerDay->add(new \DateInterval('P26D'));

        $feastOfTheAscension = clone $easter;
        $feastOfTheAscension->add(new \DateInterval('P39D'));

        $pentecost = clone $easter;
        $pentecost->add(new \DateInterval('P49D'));

        $whitMonday = clone $easter;
        $whitMonday->add(new \DateInterval('P50D'));

        $holidays   = [];
        $holidays[] = $palmSunday->format('m-d');
        $holidays[] = $maundyThursday->format('m-d');
        $holidays[] = $goodFriday->format('m-d');
        $holidays[] = $easter->format('m-d');
        $holidays[] = $easterMonday->format('m-d');
        $holidays[] = $commonPrayerDay->format('m-d');
        $holidays[] = $feastOfTheAscension->format('m-d');
        $holidays[] = $pentecost->format('m-d');
        $holidays[] = $whitMonday->format('m-d');

        return in_array($day, $holidays);
    }
}