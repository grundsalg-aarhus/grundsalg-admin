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
   * @Route("/udstykning/{udstykningsId}/grunde", name="pub_api_grunde")
   */
  public function grundeAction(Request $request, $udstykningsId) {
    $em = $this->getDoctrine()->getManager();
    $query = $em->getRepository('AppBundle:Grund')
      ->getGrundeForSalgsOmraade($udstykningsId);

    $grunde = $query->getResult();

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
      'city' => $salgsomraade->getPostbyid()->getCity(),
      'postalCode' => $salgsomraade->getPostbyid()->getPostalcode(),
    ];

    $response = $this->json($data);
    $response->headers->set('Access-Control-Allow-Origin', '*');

    return $response;
  }
}
