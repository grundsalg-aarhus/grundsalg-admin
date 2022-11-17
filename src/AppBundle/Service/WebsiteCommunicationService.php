<?php
/**
 * @file
 * Contains the GrundsalgCommunicationService which handles synchronizations from
 * this system to the Grundsalg presentations web page.
 */

namespace AppBundle\Service;

use AppBundle\Entity\Salgsomraade;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class GrundsalgCommunicationService
 *
 * @package AppBundle
 */
class WebsiteCommunicationService
{
  private $endpoint;
  private $apikey;
  private $basicAuthUser;
  private $basicAuthPassword;

  private $crsPropertiesHref;
  private $crsPropertiesType;

  /**
   * @var GrundsalgPublicPropertiesService
   */
  private $publicPropertiesService;

  /**
   * @var EntityManager
   */
  private $entityManager;

  /**
   * Constructor
   */
  public function __construct(EntityManager $entityManager, GrundsalgPublicPropertiesService $publicPropertiesService, $endpoint, $apikey, $crsPropertiesHref, $crsPropertiesType, $basicAuthUser = '', $basicAuthPassword = '')
  {
    $this->entityManager = $entityManager;
    $this->publicPropertiesService = $publicPropertiesService;
    $this->endpoint = $endpoint;
    $this->apikey = $apikey;
    $this->crsPropertiesHref = $crsPropertiesHref;
    $this->crsPropertiesType = $crsPropertiesType;
    $this->basicAuthUser = $basicAuthUser;
    $this->basicAuthPassword = $basicAuthPassword;
  }

  /**
   * Handle save of salgsomraade.
   *
   * @param Salgsomraade $salgsomraade
   *
   * @return array
   *   The content received as json from the remote system.
   *
   * @throws GuzzleException
   */
  public function syncDataToWebsite(Salgsomraade $salgsomraade)
  {
    $client = new Client();

    // @TODO update/implement endpoints on the drupal side.
    // @TODO pool calls in one?
    // @TODO update output from SalgsomraadeSyncCommand

    $response1 = $client->request(
      'POST',
      $this->endpoint . "/api/udstykning/" . $salgsomraade->getId() . "/updated",
      [
        'auth' => [$this->basicAuthUser, $this->basicAuthPassword],
        'headers'=> [
          'Authorization' => 'Token '.$this->apikey,
        ],
        'json' => $this->getSalgsomraadePublicFields($salgsomraade)
      ]
    );

    // Wait 0.3 sec to give drupal process time
    \usleep(300000);

    $response2 = $client->request(
      'POST',
      $this->endpoint . "/api/udstykning/" . $salgsomraade->getId() . "/grunde",
      [
        'auth' => [$this->basicAuthUser, $this->basicAuthPassword],
        'headers'=> [
          'Authorization' => 'Token '.$this->apikey,
        ],
        'json' => $this->getGrundePublicFields($salgsomraade->getId())
      ]
    );

    // Wait 0.3 sec to give drupal process time
    \usleep(300000);

    $response3 = $client->request(
      'POST',
      $this->endpoint . "/api/udstykning/" . $salgsomraade->getId() . "/grunde/geojson",
      [
        'auth' => [$this->basicAuthUser, $this->basicAuthPassword],
        'headers'=> [
          'Authorization' => 'Token '.$this->apikey,
        ],
        'json' => $this->getGrundeGeoJsonFields($salgsomraade->getId())
      ]
    );

    // Wait 0.3 sec to give drupal process time
    \usleep(300000);

    $body = $response1->getBody()->getContents();
    $content = \GuzzleHttp\json_decode($body);

    return (array)$content;
  }

  public function getSalgsomraadePublicFields(Salgsomraade $salgsomraade): array
  {
    return [
      'id' => $salgsomraade->getId(),
      'type' => $salgsomraade->getType(),
      'title' => $salgsomraade->getTitel(),
      'vej' => $salgsomraade->getVej(),
      'city' => $salgsomraade->getPostby() ? $salgsomraade->getPostby()->getCity() : null,
      'postalCode' => $salgsomraade->getPostby() ? $salgsomraade->getPostby()->getPostalcode() : null,
      'geometry' => $salgsomraade->getSpGeometryArray(),
      'srid' => $salgsomraade->getSrid(),
      'publish' => $salgsomraade->isAnnonceres(),
    ];
  }

  public function getGrundeGeoJsonFields(int $salgsomraadeId): array
  {
    $grunde = $this->entityManager->getRepository('AppBundle:Grund')->getGrundeForSalgsOmraade($salgsomraadeId);

    $list = [];
    $list['type'] = 'FeatureCollection';

    $crs = [];
    $crs['type'] = 'link';
    $crs['properties']['href'] = $this->crsPropertiesHref;
    $crs['properties']['type'] = $this->crsPropertiesType;
    $list['crs'] = $crs;

    $list['features'] = [];

    foreach ($grunde as $grund) {
      $data = [];

      $data['type'] = 'Feature';
      $data['geometry'] = $grund->getSpGeometryArray();

      $properties = [
        'id' => $grund->getId(),
        'address' => trim($grund->getVej() . ' ' . $grund->getHusnummer() . $grund->getBogstav()),
        'status' => $this->publicPropertiesService->getPublicStatus($grund),
        'area_m2' => intval($grund->getAreal()),
        // @TODO which fields to map for prices?
        'minimum_price' => $this->publicPropertiesService->getPublicMinPris($grund),
        'sale_price' => $this->publicPropertiesService->getPublicPris($grund),
        'pdf_link' => $grund->getPdfLink(),

        // Needed in the frontend/weblayer. If true popup will be enabled for the feature.
        'markers' => true,
      ];

      $data['properties'] = $properties;

      $list['features'][] = $data;
    }

    return $list;
  }

  public function getGrundePublicFields(int $salgsomraadeId): array
  {
    $grunde = $this->entityManager->getRepository('AppBundle:Grund')->getGrundeForSalgsOmraade($salgsomraadeId);

    $list = [];

    $list['count'] = count($grunde);
    $list['grunde'] = [];

    foreach ($grunde as $grund) {
      $data = [
        'id' => $grund->getId(),
        'address' => trim($grund->getVej() . ' ' . $grund->getHusnummer() . $grund->getBogstav()),
        'status' => $this->publicPropertiesService->getPublicStatus($grund),
        'area_m2' => intval($grund->getAreal()),
        'minimum_price' => $this->publicPropertiesService->getPublicMinPris($grund),
        'sale_price' => $this->publicPropertiesService->getPublicPris($grund),
        'pdf_link' => $grund->getPdfLink(),
      ];

      $list['grunde'][] = $data;
    }

    return $list;
  }
}
