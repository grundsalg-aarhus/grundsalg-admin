<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Grund;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/public/api")
 */
class ApiController extends Controller {
  /**
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
        $data['geometry'] = $grund->getSpGeometryGeoJsonObject();

        $properties['id'] = $grund->getId();
        $properties['address'] = trim($grund->getVej() . ' ' . $grund->getHusnummer() . $grund->getBogstav());
        $properties['status'] = $grund->getStatus();
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
        $data['status'] = $grund->getStatus();
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
   * @Route("/udstykning/{udstykningsId}", name="pub_api_salgsomraade")
   */
  public function salgsomraadeAction(Request $request, $udstykningsId) {
    $em = $this->getDoctrine()->getManager();
    $area = $em->getRepository('AppBundle:Salgsomraade')->findOneById($udstykningsId);

    /**
     * @TODO: Add status to set published state.
     */
    $data = [
      'id' => $area->getId(),
      'type' => $area->getType(),
      'title' => $area->getTitel(),
      'vej' => $area->getVej(),
      'city' => $area->getPostby() ? $area->getPostby()->getCity() : null,
      'postalCode' => $area->getPostby() ? $area->getPostby()->getPostalcode() : null,
      'geometry' => $area->getSpGeometry(),
      'srid' => $area->getSrid(),
    ];

    $response = $this->json($data);
    $response->headers->set('Access-Control-Allow-Origin', '*');

    return $response;
  }
}
