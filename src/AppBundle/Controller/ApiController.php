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
class ApiController extends Controller {
  /**
   * @TODO: Missing documentation!
   *
   * @Route("/udstykning/{udstykningsId}/grunde/{format}", name="pub_api_grunde")
   */
  public function grundeAction(Request $request, $udstykningsId, $format = 'drupal_api') {
    $em = $this->getDoctrine()->getManager();
    $query = $em->getRepository('AppBundle:Grund')->getGrundeForSalgsOmraade($udstykningsId);

    $grunde = $query->getResult();
    $list = array();

    if($format === 'geojson') {
      $list['type'] = 'FeatureCollection';

      $crs = array();
      $crs['type'] = 'link';
      $crs['properties']['href'] = 'http://spatialreference.org/ref/epsg/25832/proj4/';
      $crs['properties']['href'] = $this->getParameter('crs_properties_href');
      $crs['properties']['type'] = $this->getParameter('crs_properties_type');
      $list['crs'] = $crs;

      $list['features'] = array();

      foreach ($grunde as $grund) {
        $data = array();

        $data['type'] = 'Feature';
        $data['geometry'] = $grund->getSpGeometryArray();

        $properties['id'] = $grund->getId();
        $properties['address'] = trim($grund->getVej() . ' ' . $grund->getHusnummer() . $grund->getBogstav());
        $properties['status'] = $this->getPublicStatus($grund);
        $properties['area_m2'] = $grund->getAreal();
        // @TODO which fields to map for prices?
        $properties['minimum_price'] = $grund->getMinbud();
        $properties['sale_price'] = $grund->getPris();
        $properties['pdf_link'] = $grund->getPdfLink();

        // Needed in the frontend/weblayer. If true popup will be enabled for the feature.
        $properties['markers'] = true;

        $data['properties'] = $properties;

        $list['features'][] = $data;
      }

    } else {

      $list['count'] = count($grunde);
      $list['grunde'] = array();

      foreach ($grunde as $grund) {
        $data = array();
        $data['id'] = $grund->getId();
        $data['address'] = trim($grund->getVej() . ' ' . $grund->getHusnummer() . $grund->getBogstav());
        $data['status'] = $this->getPublicStatus($grund);
        $data['area_m2'] = $grund->getAreal();
        // @TODO which fields to map for prices?
        $data['minimum_price'] = $grund->getMinbud();
        $data['sale_price'] = $grund->getPris();
        $data['pdf_link'] = $grund->getPdfLink();

        $list['grunde'][] = $data;
      }

    }

    $response = $this->json($list);
    $response->headers->set('Access-Control-Allow-Origin', '*');

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
  public function salgsomraadeAction(Request $request, $udstykningsId) {
    $em = $this->getDoctrine()->getManager();
    $area = $em->getRepository('AppBundle:Salgsomraade')->findOneById($udstykningsId);

    $data = [
      'id' => $area->getId(),
      'type' => $area->getType(),
      'title' => $area->getTitel(),
      'vej' => $area->getVej(),
      'city' => $area->getPostby() ? $area->getPostby()->getCity() : null,
      'postalCode' => $area->getPostby() ? $area->getPostby()->getPostalcode() : null,
      'geometry' => $area->getSpGeometryArray(),
      'srid' => $area->getSrid(),
      'publish' => $area->isAnnonceres(),
    ];

    $response = $this->json($data);
    $response->headers->set('Access-Control-Allow-Origin', '*');

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
   * @return string
   */
  private function getPublicStatus(Grund $grund) {
    $status = $grund->getStatus();
    $salgStatus = $grund->getSalgstatus();

    if($salgStatus === SalgStatus::ACCEPTERET) {
      return PublicStatus::SOLGT;
    }

    if($status === Status::FREMTIDIG && $salgStatus === SalgStatus::LEDIG) {
      return PublicStatus::FREMTIDIG;
    }

    if($status === Status::LEDIG && $salgStatus === SalgStatus::LEDIG) {
      return PublicStatus::LEDIG;
    }

    if($status === Status::SOLGT && $salgStatus === SalgStatus::LEDIG) {
      return PublicStatus::LEDIG;
    }

    if($status === Status::LEDIG && $salgStatus === SalgStatus::RESERVERET) {
      return PublicStatus::RESERVERET;
    }

    if($status === Status::LEDIG && $salgStatus === SalgStatus::SKOEDE_REKVIRERET) {
      return PublicStatus::SOLGT;
    }

    if($status === Status::AUKTION_SLUT && $salgStatus === SalgStatus::SKOEDE_REKVIRERET) {
      return PublicStatus::SOLGT;
    }

    if($status === Status::LEDIG && $salgStatus == SalgStatus::SOLGT) {
      return PublicStatus::SOLGT;
    }

    if($status === Status::AUKTION_SLUT && $salgStatus === SalgStatus::SOLGT) {
      return PublicStatus::SOLGT;
    }

    if($status === Status::ANNONCERET && $salgStatus === SalgStatus::LEDIG) {
      return PublicStatus::I_UDBUD;
    }

    return PublicStatus::SOLGT;

  }
}
