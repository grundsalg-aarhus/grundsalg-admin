<?php

namespace AppBundle\Controller;

use AppBundle\DBAL\Types\GrundType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class StatisticsController extends EasyAdminController
{
  /**
   * @Route(path = "/statistics/betalte/{type}/{showYear}", name = "grundsalg_statistics_betalte")
   */
  public function betalteTypeYearAction(Request $request, $type, $showYear)
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
   * @Route(path = "/statistics/betalte/{type}", name = "grundsalg_statistics_betalte_overview")
   */
  public function betalteOverviewAction(Request $request, $type = GrundType::PARCELHUS)
  {
    $menuIndex = $request->query->get('menuIndex');
    $submenuIndex = $request->query->get('submenuIndex');

    $firstYear = $this->getParameter('grundsalg_first_year');
    $currentYear = date('Y');
    $showYear = null;

    $repository = $this->get('doctrine')->getRepository('AppBundle:Grund');
    $result = $repository->getStatsBetalteIalt($type);
    $total = $repository->getStatsBetalteIalt($type, false);

    return $this->render('statistics/betalte_overview.html.twig', array(
      'result' => $result,
      'total' => $total,
      'type' => $type,
      'first_year' => $firstYear,
      'current_year' => $currentYear,
      'show_year' => $showYear,
      'menuIndex' => $menuIndex,
      'submenuIndex' => $submenuIndex,
    ));

  }

  /**
   * @Route(path = "/statistics/omraade/{type}/{salgsomraadeId}", name = "grundsalg_statistics_omraade")
   */
  public function omraadeTypeAction(Request $request, $type, $salgsomraadeId)
  {
    $menuIndex = $request->query->get('menuIndex');
    $submenuIndex = $request->query->get('submenuIndex');

    $repository = $this->get('doctrine')->getRepository('AppBundle:Salgsomraade');
    $salgsomraade = $repository->find($salgsomraadeId);

    if ($salgsomraade === null) {
      return $this->redirectToRoute('grundsalg_statistics_omraade_overview', ['type' => $type]);
    }

    $repository = $this->get('doctrine')->getRepository('AppBundle:Grund');
    $result = $repository->getStatsOmraadeIalt($type);
    $stats = $repository->getStatsOmraadeByType($type, $salgsomraade);
    $total = $repository->getStatsOmraade($type, $salgsomraade);

    $grundeByYear = array();
    foreach ($stats as $r) {
      $grundeByYear[$r['year']] = $repository->getGrundeByTypeSalgsomraade($type, $salgsomraade, $r['year']);
    }

    return $this->render('statistics/omraade.html.twig', array(
      'result' => $result,
      'stats' => $stats,
      'salgsomraade' => $salgsomraade,
      'total' => $total,
      'grunde' => $grundeByYear,
      'type' => $type,
      'menuIndex' => $menuIndex,
      'submenuIndex' => $submenuIndex,
    ));

  }

  /**
   * @Route(path = "/statistics/omraade/{type}", name = "grundsalg_statistics_omraade_overview")
   */
  public function omraadeOverviewAction(Request $request, $type = GrundType::PARCELHUS)
  {
    $menuIndex = $request->query->get('menuIndex');
    $submenuIndex = $request->query->get('submenuIndex');

    $repository = $this->get('doctrine')->getRepository('AppBundle:Grund');
    $result = $repository->getStatsOmraadeIalt($type);
    $total = $repository->getStatsOmraadeIalt($type, false);

    return $this->render('statistics/omraade_overview.html.twig', array(
      'result' => $result,
      'total' => $total,
      'type' => $type,
      'menuIndex' => $menuIndex,
      'submenuIndex' => $submenuIndex,
    ));

  }

  /**
   * @Route(path = "/statistics/grunde", name = "grundsalg_statistics_alle_grunde")
   */
  public function alleGrundeAction(Request $request)
  {
    $menuIndex = $request->query->get('menuIndex');
    $submenuIndex = $request->query->get('submenuIndex');

    $repository = $this->get('doctrine')->getRepository('AppBundle:Grund');
    $result = $repository->getStatsAlleGrunde();

    return $this->render('statistics/alle_grunde.html.twig', array(
      'result' => $result,
      'menuIndex' => $menuIndex,
      'submenuIndex' => $submenuIndex,
    ));
  }

}
