<?php
/**
 * @file
 * Contains the GrundsalgCommunicationService which handles synchronizations from
 * this system to the Grundsalg presentations web page.
 */

namespace AppBundle\Service;

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
class GrundsalgPublicStatusService
{

    /**
     * Constructor
     */
    public function __construct()
    {
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
}