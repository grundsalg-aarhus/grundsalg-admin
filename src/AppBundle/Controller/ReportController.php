<?php

namespace AppBundle\Controller;

use AppBundle\DBAL\Types\GrundType;
use AppBundle\Report\Report;
use Box\Spout\Writer\WriterFactory;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ReportController
 * @package AppBundle\Controller
 *
 * @route("/report")
 */
class ReportController extends Controller {
  /**
   * @Route(path = "/", name = "grundsalg_report")
   * @Template("report/index.html.twig")
   */
  public function indexAction(Request $request) {
    $reports = $this->getReports();
    $parameterForms = [];
    foreach ($reports as $name => $report) {
      $view = $this->buildParameterForm($report)->createView();
      $parameterForms[$name] = $view;
    }

    // header('Content-type: text/plain'); echo var_export($parameterForms, true); die(__FILE__.':'.__LINE__.':'.__METHOD__);

    return [
      'reports' => $reports,
      'parameterForms' => $parameterForms,
      'grundTypes' => $this->getGrundTypes(),
    ];
  }

  /**
   * @Route(
   *   name = "grundsalg_report_generate",
   *   path = "/generate.{_format}",
   *   defaults={"_format": "xlsx"},
   *   requirements={
   *     "_format": "xlsx|csv",
   *   }
   * )
   */
  public function generateAction(Request $request, $_format) {
    $reportKey = $request->get('report');
    $reportClass = NULL;
    foreach ($this->getReports() as $r) {
      if ($this->getReportKey($r)) {
        $reportClass = get_class($r);
        break;
      }
    }
    if (!$reportClass || !is_subclass_of($reportClass, Report::class)) {
      $this->addFlash('danger', 'Invalid report');
      return $this->redirectToRoute('grundsalg_report');
    }

    /** @var Report $report */
    $report = new $reportClass($this->getDoctrine()->getManager());
    $form = $this->buildParameterForm($report);
    $form->submit($request->get($form->getName()));
    $parameters = $form->getData();

    $filename = $this->getFilename($_format);

    $writer = WriterFactory::create($_format);
    $writer->openToBrowser($filename);
    $report->write($parameters, $writer);
    $writer->close();
    exit;
  }

  private function getFilename($format) {
    return 'export.' . $format;
  }

  private static $contentTypes = [
    'xlsx' => 'application/vnd.ms-excel',
    'csv' => 'text/csv; charset: utf-8',
  ];

  private function getContentType($format) {
    return isset(static::$contentTypes[$format]) ? static::$contentTypes[$format] : static::$contentTypes['xsls'];
  }

  private function getReports() {
    $reports = [];

    foreach ($this->container->get('app.report_manager')->getReports() as $report) {
      $reports[$this->getReportKey($report)] = $report;
    }

    return $reports;
  }

private function getReportKey(Report $report) {
    return md5(get_class($report));
}

  private function getGrundTypes() {
    return GrundType::getChoices();
  }

  private function buildParameterForm(Report $report) {
    $parameters = $report->getParameters();

    $builder = $this->get('form.factory')->createNamedBuilder('parameters_' . md5(get_class($report)));
    foreach ($parameters as $name => $config) {
      $type = isset($config['type']) ? $config['type'] : NULL;
      $typeOptions = isset($config['type_options']) ? $config['type_options'] : [];
      $builder->add($name, $type, $typeOptions);
    }

    return $builder->getForm();
  }

  /**
   * @Route(
   *   name = "grundsalg_report_debug",
   *   path = "/debug",
   * )
   */
  public function debugAction(Request $request) {
    //*
    $this->entityManager = $this->getDoctrine()->getManager();
    $this->parameters = [
      'grundtype' => 'Parcelhusgrund',
      'startdate' => new \DateTime('2000-01-01'),
      'enddate' => new \DateTime('2100-01-01'),
    ];
    $sql = "SELECT g.lokalSamfundId,s.name, count(g.vej) as aktuelle, SUM(CASE WHEN SalgStatus='Solgt' THEN 1  ELSE 0 END) as solgt, ";
    $sql .= "SUM(CASE WHEN SalgStatus='Accepteret' OR SalgStatus='Skøde rekvireret' THEN 1  ELSE 0 END) as accept, ";
    $sql .= "SUM(CASE WHEN SalgStatus='Reserveret' THEN 1  ELSE 0 END) as res ";
    $sql .= "FROM Grund as g ";
    $sql .= "JOIN Lokalsamfund as s on s.id=g.lokalSamfundId ";
    $sql .= "WHERE g.type= :grundtype and not ( ";
    $sql .= "(beloebAnvist is not null and beloebAnvist < :fromDate) or ";
    $sql .= "(datoAnnonce1 is not null And datoAnnonce1 > :toDate) or ";
    $sql .= "(datoAnnonce1 is null And (datoAnnonce is not null And datoAnnonce > :toDate)) or ";
    $sql .= "(( auktionStartDato is not null And auktionStartDato > :toDate) And ( datoAnnonce1 is null Or datoAnnonce1 > :toDate)) or ";
    $sql .= "(status = 'Fremtidig' And annonceres = 1)";
    $sql .= ") ";
    $sql .= "group by g.lokalSamfundId,s.name order by s.name ";

    $stmt = $this->entityManager->getConnection()->prepare($sql);
    $stmt->bindValue(':grundtype' , $this->parameters['grundtype']);
    $stmt->bindValue(':fromDate' , $this->parameters['startdate'], Type::DATE);
    $stmt->bindValue(':toDate' , $this->parameters['enddate'], Type::DATE);
    $stmt->execute();
    $rows = $stmt->fetchAll();
    header('Content-type: text/plain'); echo var_export($rows, true); die(__FILE__.':'.__LINE__.':'.__METHOD__);
//*/
  }
}
