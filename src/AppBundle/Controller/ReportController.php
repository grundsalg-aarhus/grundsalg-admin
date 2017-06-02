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
      if ($this->getReportKey($r) === $reportKey) {
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

    $filename = $this->getFilename($parameters, $report->getTitle(), $_format);

    $writer = WriterFactory::create($_format);
    $writer->openToBrowser($filename);
    $report->write($parameters, $writer);
    $writer->close();
    exit;
  }

  private function getFilename($parameters, $title, $format) {
    $title = str_replace(' ', '-', $title);
    if(array_key_exists('grundtype', $parameters)) {
      return $parameters['grundtype'] . '_' . $title . '.' . $format;
    }

    return $title . '.' . $format;
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

}
