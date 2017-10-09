<?php

namespace AppBundle\Controller;

use AppBundle\DBAL\Types\GrundType;
use AppBundle\DBAL\Types\SalgsType;
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

        $publicStatusService = $this->get('grundsalg.public_status');

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
                $properties['status']  = $publicStatusService->getPublicStatus($grund);
                $properties['area_m2'] = $grund->getAreal();
                // @TODO which fields to map for prices?
                $properties['minimum_price'] = $this->getPublicMinPris($grund);
                $properties['sale_price']    = $this->getPublicPris($grund);
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
                $data['status']  = $publicStatusService->getPublicStatus($grund);
                $data['area_m2'] = $grund->getAreal();
                $data['minimum_price'] = $this->getPublicMinPris($grund);
                $data['sale_price']    = $this->getPublicPris($grund);
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
     * Get the public display price accounting for the 6 day wait period for private sales.
     *
     * @param Grund $grund
     *
     * @return float|int
     */
    private function getPublicPris(Grund $grund)
    {
        $holidayService = $this->get('grundsalg.bank_holiday');

        if($grund->getType() === GrundType::PARCELHUS && $holidayService->isWaitingPeriod($grund)) {
            return 0;
        }

        if($grund->getType() !== GrundType::PARCELHUS) {
            return $grund->getSalgsprisumoms() ?? 0;
        }

        return $grund->getPris() ?? 0;
    }

    private function getPublicMinPris(Grund $grund)
    {
        switch ($grund->getSalgstype()) {
            case SalgsType::AUKTION:
                return $grund->getMinbud();
            case SalgsType::FASTPRIS:
                return $grund->getFastpris();
            default:
                return $grund->getPris();
        }
    }

}
