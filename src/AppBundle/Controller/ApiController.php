<?php

namespace AppBundle\Controller;

use AppBundle\DBAL\Types\GrundType;
use AppBundle\DBAL\Types\SalgsType;
use AppBundle\Entity\Grund;
use AppBundle\Service\WebsiteCommunicationService;
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
class ApiController extends Controller
{
  /**
   * @var WebsiteCommunicationService
   */
  private $communicationService;

  public function __construct(WebsiteCommunicationService $communicationService)
  {
    $this->communicationService = $communicationService;
  }

  /**
     * @TODO: Missing documentation!
     *
     * @Route("/udstykning/{udstykningsId}/grunde/{format}", name="pub_api_grunde")
     */
    public function grundeAction(Request $request, $udstykningsId, $format = 'drupal_api')
    {
        if ($format === 'geojson') {
          $list = $this->communicationService->getGrundeGeoJsonFields($udstykningsId);
        } else {
          $list = $this->communicationService->getGrundePublicFields($udstykningsId);
        }

        $response = $this->json($list);

        $response->setPrivate();
        $response->setMaxAge(0);
        $response->setSharedMaxAge(0);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->headers->addCacheControlDirective('no-store', true);

        return $response;
    }

    /**
     * Returns JSON with information about a "Salgsomraade".
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
    public function salgsomraadeAction(Request $request, int $udstykningsId)
    {
        $em   = $this->getDoctrine()->getManager();
        $area = $em->getRepository('AppBundle:Salgsomraade')->findOneById($udstykningsId);
        $data = [];

        if ($area->isAnnonceres()) {
            $data = $this->communicationService->getSalgsomraadePublicFields($area);
        }

        $response = $this->json($data);

        $response->setPrivate();
        $response->setMaxAge(0);
        $response->setSharedMaxAge(0);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->headers->addCacheControlDirective('no-store', true);

        return $response;
    }

}
