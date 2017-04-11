<?php

namespace AppBundle\Controller;

use AppBundle\DBAL\Types\GrundType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;

class StatisticsController extends BaseAdminController
{

  /**
   * @Route(path = "/statistics/betalte/{type}/{year}", name = "grundsalg_statistics_betalte")
   */
  public function betalteAction(Request $request, $type = GrundType::PARCELHUS, $year=null)
  {
    $year = ($year === null) ? date('Y') : $year;

    $repository = $this->get('doctrine')->getRepository('AppBundle:Grund');
    $result = $repository->getStatsBetalte($type, $year);
    $grundeByQuarter = array();
    for($i = 1; $i <= 4; $i++) {
      $grundeByQuarter[$i] = $repository->getGrundeByTypeYear($type, $year, $i);
    }

    return $this->render('statistics/betalte.html.twig', array(
      'result' => $result,
      'grunde' => $grundeByQuarter,
      'type' => $type,
      'year' => $year
    ));

  }

  /**
   * @Route(path = "/statistics", name = "grundsalg_statistics")
   */
  public function statisticsAction(Request $request)
  {

    $repository = $this->get('doctrine')->getRepository('AppBundle:Grund');
    $result = $repository->getBetalteIalt(GrundType::PARCELHUS);

    return $this->render('statistics/index.html.twig', array(
      'result' => $result,
    ));

  }

}