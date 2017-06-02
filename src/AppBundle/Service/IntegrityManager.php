<?php

namespace AppBundle\Service;

use AppBundle\Entity\Delomraade;
use AppBundle\Entity\Event;
use AppBundle\Entity\Grund;
use AppBundle\Entity\Interessent;
use AppBundle\Entity\Keyword;
use AppBundle\Entity\KeywordValue;
use AppBundle\Entity\Landinspektoer;
use AppBundle\Entity\Lokalplan;
use AppBundle\Entity\Lokalsamfund;
use AppBundle\Entity\Occurrence;
use AppBundle\Entity\Opkoeb;
use AppBundle\Entity\Organizer;
use AppBundle\Entity\Place;
use AppBundle\Entity\Postby;
use AppBundle\Entity\Reservation;
use AppBundle\Entity\Salgshistorik;
use AppBundle\Entity\Salgsomraade;
use AppBundle\Entity\Tag;
use AppBundle\Entity\Tagging;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class for deciding if entities can be deleted without voilating referential integrety.
 */
class IntegrityManager {
  /**
   * An entity manager.
   *
   * @var \Doctrine\ORM\EntityManagerInterface
   */
  private $entityManager;

  /**
   * Constructor.
   */
  public function __construct(EntityManagerInterface $entityManager) {
    $this->entityManager = $entityManager;
  }

  /**
   * Decide if an entity can be deleted without voilating referential integrety.
   *
   * @param object $entity
   *   The entity.
   *
   * @return bool|array
   *   Return true iff entity can be safely deleted. Otherwise, return info on entities with references to the entity.
   */
  public function canDelete($entity) {
    $entityAssociations = $this->getAssocications($entity);

    // Get references to the entity up for deletion.
    $references = [];
    foreach ($entityAssociations as $className => $associations) {
      foreach ($associations as $association) {
        $fieldName = $association['fieldName'];
        $count = $this->getNumberOfReferences($className, $fieldName, $entity);
        if ($count > 0) {
          if (!isset($references['total'])) {
            $references['total'] = 0;
            $references['references'] = [];
          }
          $references['total'] += $count;
          if (!isset($references['references'][$className])) {
            $references['references'][$className] = [];
          }
          $references['references'][$className][$fieldName] = $count;
        }
      }
    }

    return $references ?: TRUE;
  }

  /**
   * Get all associations targeting a specified entity class.
   *
   * @param object $entity
   *   The entity.
   *
   * @return array
   *   class name => [ associations ]
   */
  private function getAssocications($entity) {
    $allAssociations = [];

    $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
    foreach ($metadata as $metadatum) {
      $associations = array_filter($metadatum->getAssociationMappings(), function ($association) use ($entity) {
        return !isset($association['inherited']) && $association['targetEntity'] === get_class($entity);
      });
      foreach ($associations as $association) {
        $className = $association['sourceEntity'];
        if (!isset($allAssociations[$className])) {
          $allAssociations[$className] = [];
        }
        $allAssociations[$className][] = $association;
      }
    }

    return $allAssociations;
  }

  /**
   * Get number of references from a class property to an entity.
   *
   * @param string $className
   *   The class name.
   * @param string $fieldName
   *   The field name.
   * @param object $entity
   *   The entity.
   *
   * @return int
   *   The number of references to the entity.
   */
  private function getNumberOfReferences($className, $fieldName, $entity) {
    $queryBuilder = $this->entityManager->getRepository($className)->createQueryBuilder('e');
    $queryBuilder
      ->select($queryBuilder->expr()->count('e.id'))
      ->where('e.' . $fieldName . ' = :target')
      ->setParameter('target', $entity);

    return (int) $queryBuilder->getQuery()->getSingleScalarResult();
  }

}
