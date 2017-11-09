<?php
/**
 * @file
 * Contains the GrundsalgCommunicationService which handles synchronizations from
 * this system to the Grundsalg presentations web page.
 */

namespace AppBundle\Service;

use AppBundle\DBAL\Types\GrundType;
use AppBundle\DBAL\Types\SalgsType;
use AppBundle\Entity\Salgsomraade;
use GuzzleHttp\Client;
use AppBundle\Entity\Grund;
use AppBundle\DBAL\Types\GrundSalgStatus as SalgStatus;
use AppBundle\DBAL\Types\GrundPublicStatus as PublicStatus;
use AppBundle\DBAL\Types\GrundStatus as Status;

/**
 * Class GrundsalgHolidayService
 *
 * @package AppBundle
 */
class GrundsalgPublicPropertiesService
{
    private $bankHolidayService;

    /**
     * Constructor
     */
    public function __construct(GrundsalgBankHolidayService $bankHolidayService)
    {
        $this->bankHolidayService = $bankHolidayService;
    }

    /**
     * Get the publicly exposed sales status based on a combination
     * of the internal fields 'status' and 'salgsstatus'.
     *
     * Domain rules (status / salgStatus):
     *    Accepteret = solgt
     *    Fremtidig / Ledig = Fremtidig
     *    Ledig/Ledig = Ledig
     *    Solgt / Ledig = Ledig
     *    Ledig/ Reserveret = Reserveret
     *    Ledig/ Skøde rekvireret = Solgt
     *    Auktion slut / Skøde rekvireret = Solgt
     *    Ledig / Solgt = Solgt
     *    Auktion slut / Solgt = Solgt
     *    Annonceret / Ledig = i udbud.
     *
     * Valid return values are {"Solgt", "Fremtidig", "Ledig", "Reserveret", "I udbud"}
     *
     * @param $grund
     *
     * @return string
     */
    public function getPublicStatus(Grund $grund)
    {

        // If 'Public status' is set explicitly we use that
        if ($grund->getPublicstatus()) {
            return $grund->getPublicstatus();
        }

        // Otherwise it depends on the combination of status and salgstatus
        $status     = $grund->getStatus();
        $salgStatus = $grund->getSalgstatus();

        // If waiting period return 'I Udbud'  - This only applies to 'parcelhus' on 'auktion'
        if ($grund->getType() === GrundType::PARCELHUS && $grund->getSalgstype() === SalgsType::AUKTION && $status === Status::AUKTION_SLUT && $this->bankHolidayService->isWaitingPeriod($grund)) {
            if ($salgStatus === SalgStatus::SKOEDE_REKVIRERET || $salgStatus === SalgStatus::ACCEPTERET || SalgStatus::SOLGT) {
                return PublicStatus::I_UDBUD;
            }
        }

        if ($salgStatus === SalgStatus::ACCEPTERET) {
            return PublicStatus::SOLGT;
        }

        if ($status === Status::FREMTIDIG && $salgStatus === SalgStatus::LEDIG) {
            return PublicStatus::FREMTIDIG;
        }

        if ($status === Status::LEDIG && $salgStatus === SalgStatus::LEDIG) {
            return PublicStatus::LEDIG;
        }

        if ($status === Status::SOLGT && $salgStatus === SalgStatus::LEDIG) {
            return PublicStatus::LEDIG;
        }

        if ($status === Status::LEDIG && $salgStatus === SalgStatus::RESERVERET) {
            return PublicStatus::RESERVERET;
        }

        if ($status === Status::LEDIG && $salgStatus === SalgStatus::SKOEDE_REKVIRERET) {
            return PublicStatus::SOLGT;
        }

        if ($status === Status::AUKTION_SLUT && $salgStatus === SalgStatus::SKOEDE_REKVIRERET) {
            return PublicStatus::SOLGT;
        }

        if ($status === Status::LEDIG && $salgStatus == SalgStatus::SOLGT) {
            return PublicStatus::SOLGT;
        }

        if ($status === Status::AUKTION_SLUT && $salgStatus === SalgStatus::SOLGT) {
            return PublicStatus::SOLGT;
        }

        if ($status === Status::ANNONCERET && $salgStatus === SalgStatus::LEDIG) {
            return PublicStatus::I_UDBUD;
        }

        if ($status === Status::AUKTION_SLUT) {
            return PublicStatus::I_UDBUD;
        }

        return PublicStatus::SOLGT;

    }


    /**
     * Get the public display price accounting for the 6 day wait period for private sales.
     *
     * @param Grund $grund
     *
     * @return float|int
     */
    public function getPublicPris(Grund $grund)
    {
        if ($grund->getType() === GrundType::PARCELHUS && $grund->getSalgstype() === SalgsType::AUKTION && $this->bankHolidayService->isWaitingPeriod($grund)) {
            $pris = 0;
        } else if ($grund->getType() !== GrundType::PARCELHUS) {
            $pris = intval($grund->getSalgsprisumoms()) ? $grund->getSalgsprisumoms() : $grund->getPris() * 0.8;
        } else {
            $pris = $grund->getPris() ?? 0;
        }

        return intval($pris);
    }

    /**
     * Get the public min pris
     *
     * @param Grund $grund
     *
     * @return \AppBundle\Entity\decimal|float|int
     */
    public function getPublicMinPris(Grund $grund)
    {
        switch ($grund->getSalgstype()) {
            case SalgsType::AUKTION:
                $minpris = $grund->getMinbud();
                break;
            case SalgsType::FASTPRIS:
                $minpris = $grund->getFastpris();
                break;
            default:
                $minpris = $grund->getPris();
        }

        if ($grund->getType() !== GrundType::PARCELHUS) {
            $minpris = $minpris ? $minpris * 0.8 : 0;
        }

        $minpris = $minpris ?? 0;

        return intval($minpris);
    }
}