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
      $list['features'] = array();

      $crs = array();
      $crs['type'] = 'link';
      $crs['properties']['href'] = 'http://spatialreference.org/ref/epsg/25832/proj4/';
      $crs['properties']['href'] = $this->getParameter('crs_properties_href');
      $crs['properties']['type'] = $this->getParameter('crs_properties_type');

      foreach ($grunde as $grund) {
        $data = array();

        $data['type'] = 'Feature';
        $data['geometry'] = $grund->getSpGeometryGeoJsonObject();
        $data['crs'] = $crs;

        $properties['id'] = $grund->getId();
        $properties['address'] = trim($grund->getVej() . ' ' . $grund->getHusnummer() . $grund->getBogstav());
        $properties['status'] = $grund->getStatus();
        $properties['area_m2'] = $grund->getAreal();
        // @TODO which fields to map for prices?
        $properties['minimum_price'] = $grund->getMinbud();
        $properties['sale_price'] = $grund->getPris();
        // @TODO add pdf link when access import complete
        $properties['pdf_link'] = 'http://todo.com/todo.pdf';

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
        // @TODO add pdf link when access import complete
        $data['pdf_link'] = 'http://todo.com/todo.pdf';

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
    $salgsomraade = $em->getRepository('AppBundle:Salgsomraade')->findOneById($udstykningsId);

    $data = [
      'id' => $salgsomraade->getId(),
      'type' => $salgsomraade->getType(),
      'title' => $salgsomraade->getTitel(),
      'city' => $salgsomraade->getPostby()->getCity(),
      'postalCode' => $salgsomraade->getPostby()->getPostalcode(),
    ];

    $response = $this->json($data);
    $response->headers->set('Access-Control-Allow-Origin', '*');

    return $response;
  }
}
