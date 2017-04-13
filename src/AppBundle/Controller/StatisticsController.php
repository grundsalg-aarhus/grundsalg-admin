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
   * @Route(path = "/statistics/betalte/{type}/{showYear}", name = "grundsalg_statistics_betalte")
   */
  public function betalteAction(Request $request, $type, $showYear)
  {
    $menuIndex = $request->query->get('menuIndex');
    $submenuIndex = $request->query->get('submenuIndex');

    $firstYear = $this->getParameter('grundsalg_first_year');
    $currentYear = date('Y');
    $showYear = ($showYear === null) ? $currentYear : $showYear;

    $repository = $this->get('doctrine')->getRepository('AppBundle:Grund');
    $result = $repository->getStatsBetalteByQuarter($type, $showYear);
    $total = $repository->getStatsBetalte($type, $showYear);

    $grundeByQuarter = array();
    for($i = 1; $i <= 4; $i++) {
      $grundeByQuarter[$i] = $repository->getGrundeByTypeYear($type, $showYear, $i);
    }

    return $this->render('statistics/betalte.html.twig', array(
      'result' => $result,
      'total' => $total,
      'grunde' => $grundeByQuarter,
      'type' => $type,
      'first_year' => $firstYear,
      'current_year' => $currentYear,
      'show_year' => $showYear,
      'menuIndex' => $menuIndex,
      'submenuIndex' => $submenuIndex,
    ));

  }

  /**
   * @Route(path = "/statistics/betalte/{type}", name = "grundsalg_statistics")
   */
  public function statisticsAction(Request $request, $type = GrundType::PARCELHUS)
  {
    $menuIndex = $request->query->get('menuIndex');
    $submenuIndex = $request->query->get('submenuIndex');

    $firstYear = $this->getParameter('grundsalg_first_year');
    $currentYear = date('Y');
    $showYear = null;

    $repository = $this->get('doctrine')->getRepository('AppBundle:Grund');
    $result = $repository->getStatsBetalteIalt($type);

    return $this->render('statistics/index.html.twig', array(
      'result' => $result,
      'type' => $type,
      'first_year' => $firstYear,
      'current_year' => $currentYear,
      'show_year' => $showYear,
      'menuIndex' => $menuIndex,
      'submenuIndex' => $submenuIndex,
    ));

  }

}