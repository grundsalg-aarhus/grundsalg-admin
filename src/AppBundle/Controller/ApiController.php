<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Grund;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\DBAL\Types\GrundStatus as Status;
use AppBundle\DBAL\Types\GrundSalgStatus as SalgStatus;
use AppBundle\DBAL\Types\GrundPublicStatus as PublicStatus;

/**
 * @Route("/public/api")
 */
class ApiController extends Controller
{
    /**
     * @TODO: Missing documentation!
     *
     * @Route("/udstykning/{udstykningsId}/grunde/{format}", name="pub_api_grunde")
     */
    public function grundeAction(Request $request, $udstykningsId, $format = 'drupal_api')
    {
        $em     = $this->getDoctrine()->getManager();
        $grunde = $em->getRepository('AppBundle:Grund')->getGrundeForSalgsOmraade($udstykningsId);

        $list = [];

        if ($format === 'geojson') {
            $list['type'] = 'FeatureCollection';

            $crs                       = [];
            $crs['type']               = 'link';
            $crs['properties']['href'] = 'http://spatialreference.org/ref/epsg/25832/proj4/';
            $crs['properties']['href'] = $this->getParameter('crs_properties_href');
            $crs['properties']['type'] = $this->getParameter('crs_properties_type');
            $list['crs']               = $crs;

            $list['features'] = [];

            foreach ($grunde as $grund) {
                $data = [];

                $data['type']     = 'Feature';
                $data['geometry'] = $grund->getSpGeometryArray();

                $properties['id']      = $grund->getId();
                $properties['address'] = trim($grund->getVej().' '.$grund->getHusnummer().$grund->getBogstav());
                $properties['status']  = $this->getPublicStatus($grund);
                $properties['area_m2'] = $grund->getAreal();
                // @TODO which fields to map for prices?
                $properties['minimum_price'] = $grund->getMinbud();
                $properties['sale_price']    = $grund->getPris();
                $properties['pdf_link']      = $grund->getPdfLink();

                // Needed in the frontend/weblayer. If true popup will be enabled for the feature.
                $properties['markers'] = true;

                $data['properties'] = $properties;

                $list['features'][] = $data;
            }

        } else {

            $list['count']  = count($grunde);
            $list['grunde'] = [];

            foreach ($grunde as $grund) {
                $data            = [];
                $data['id']      = $grund->getId();
                $data['address'] = trim($grund->getVej().' '.$grund->getHusnummer().$grund->getBogstav());
                $data['status']  = $this->getPublicStatus($grund);
                $data['area_m2'] = $grund->getAreal();
                // @TODO which fields to map for prices?
                $data['minimum_price'] = $grund->getMinbud();
                $data['sale_price']    = $grund->getPris();
                $data['pdf_link']      = $grund->getPdfLink();

                $list['grunde'][] = $data;
            }

        }

        $response = $this->json($list);

        $response->setPrivate();
        $response->setMaxAge(0);
        $response->setSharedMaxAge(0);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->headers->addCacheControlDirective('no-store', true);

        return $response;
    }

    /**
     * Returns JSON with information about a "Salgsomraad".
     *
     * @param Request $request
     *   Symfony request object.
     * @param Int $udstykningsId
     *   The id for the area to load.
     *
     * @return JsonResponse
     *   JSON encode symfony response object.
     *
     * @Route("/udstykning/{udstykningsId}", name="pub_api_salgsomraade")
     */
    public function salgsomraadeAction(Request $request, $udstykningsId)
    {
        $em   = $this->getDoctrine()->getManager();
        $area = $em->getRepository('AppBundle:Salgsomraade')->findOneById($udstykningsId);
        $data = [];

        if ($area->isAnnonceres()) {
            $data = [
                'id'         => $area->getId(),
                'type'       => $area->getType(),
                'title'      => $area->getTitel(),
                'vej'        => $area->getVej(),
                'city'       => $area->getPostby() ? $area->getPostby()->getCity() : null,
                'postalCode' => $area->getPostby() ? $area->getPostby()->getPostalcode() : null,
                'geometry'   => $area->getSpGeometryArray(),
                'srid'       => $area->getSrid(),
                'publish'    => $area->isAnnonceres(),
            ];
        }

        $response = $this->json($data);

        $response->setPrivate();
        $response->setMaxAge(0);
        $response->setSharedMaxAge(0);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->headers->addCacheControlDirective('no-store', true);

        return $response;
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
    private function getPublicStatus(Grund $grund)
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

    private function isWaitingPeriod(Grund $grund, $numberOfDays = 6)
    {

    }

    /**
     * @param Grund $grund
     * @param int $numberOfDays
     *
     * @return \DateTime
     */
    private function getWaitingPeriodEndDay(Grund $grund, $numberOfDays = 6)
    {
        $endDay = $grund->getAccept();
        $count = 0;

        while($count < $numberOfDays) {
            $endDay->add(new \DateInterval('P1D'));
            if($this->isWeekDay($endDay)) {
                $count++;
            }
        }

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
        if($dateTime->format('N') > 5 || $this->isChangingBankHoliday($dateTime) || $this->isFixedBankHoliday($dateTime)) {
            return FALSE;
        }

        return TRUE;
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
            case '12-24':  // Juleaften
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
        $dateTime->setTime(0,0,0);
        $timestamp = $dateTime->getTimestamp();

        $oneday = 86400;   // (60*60*24)
        $year   = intval(date('Y'));
        $easter = mktime(0, 0, 0, 3, (21 + (easter_days($year))), $year);

        $holidays   = [];
        $holidays[] = $easter - (7 * $oneday);  // Palmesøndag
        $holidays[] = $easter - (3 * $oneday);  // Skærtorsdag
        $holidays[] = $easter - (2 * $oneday);  // Langfredag
        $holidays[] = $easter;                  // Påskedag
        $holidays[] = $easter + (1 * $oneday);  // 2. påskedag
        $holidays[] = $easter + (26 * $oneday); // Store bededag
        $holidays[] = $easter + (39 * $oneday); // Kristi Himmelfartsdag
        $holidays[] = $easter + (49 * $oneday); // Pinsedag
        $holidays[] = $easter + (50 * $oneday); // 2. pinsedag

        return in_array($timestamp, $holidays);
    }
}
