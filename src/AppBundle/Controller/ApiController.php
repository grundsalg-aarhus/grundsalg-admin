<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/public/api")
 */

class ApiController extends Controller
{
    /**
     * @Route("/grunde", name="pub_api_grunde")
     */
    public function grundeAction(Request $request)
    {
      $em = $this->getDoctrine()->getManager();
      $grunde = $em->getRepository('AppBundle:Grund')->getActiveSales();

      $list = array();

      foreach ($grunde as $grund) {
        $data = array();
        $data['id'] = $grund['id'];
        $data['address'] = trim($grund['vej'].' '.$grund['husnummer'].$grund['bogstav']);
        $data['status'] = $grund['status'];
        $data['area_m2'] = $grund['areal'];
        // @TODO which fields to map for prices?
        $data['minimum_price'] = $grund['pris'];
        $data['sale_price'] = $grund['pris'];
        // @TODO add pdf link when access import complete
        $data['pdf_link'] = 'http://todo.com/todo.pdf';

        $list[] = $data;
      }

      $response = $this->json($list);
      $response->headers->set('Access-Control-Allow-Origin', '*');

      return $response;
    }
}
