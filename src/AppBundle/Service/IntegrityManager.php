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

class IntegrityManager {
  /**
   * @var \Doctrine\ORM\EntityManagerInterface
   */
  private $entityManager;

  public function __construct(EntityManagerInterface $entityManager) {
    $this->entityManager = $entityManager;
  }

  /**
   * @param $entity
   *
   * @return boolean|string
   *   Return true iff entity can be safely deleted. Otherwise, return a message telling why it cannot be deleted.
   */
  public function canDelete($entity) {
    if ($entity instanceof Postby) {
      return $this->canDeletePostby($entity);
    }
    if ($entity instanceof Lokalplan) {
      return $this->canDeleteLokalplan($entity);
    }
    if ($entity instanceof Landinspektoer) {
      return $this->canDeleteLandinspektoer($entity);
    }
    if ($entity instanceof Lokalsamfund) {
      return $this->canDeleteLokalsamfund($entity);
    }
    if ($entity instanceof Salgsomraade) {
      return $this->canDeleteSalgsomraade($entity);
    }
    if ($entity instanceof Keyword) {
      return $this->canDeleteKeyword($entity);
    }
    if ($entity instanceof Grund) {
      return $this->canDeleteGrund($entity);
    }
    if ($entity instanceof Delomraade) {
      return $this->canDeleteDelomraade($entity);
    }
    if ($entity instanceof Interessent) {
      return $this->canDeleteInteressent($entity);
    }

    return TRUE;
  }

  private function canDeletePostby($entity) {
    return $this->canDeleteEntity($entity, [
      // Grund.php:136:   * @ORM\ManyToOne(targetEntity="Postby")
      [Grund::class, 'postby'],
      // Grund.php:535:   * @ORM\ManyToOne(targetEntity="Postby")
      [Grund::class, 'koeberPostby'],
      // Grund.php:545:   * @ORM\ManyToOne(targetEntity="Postby")
      [Grund::class, 'medkoeberPostby'],
      // Interessent.php:132:   * @ORM\ManyToOne(targetEntity="Postby")
      [Interessent::class, 'koeberPostby'],
      // Interessent.php:142:   * @ORM\ManyToOne(targetEntity="Postby")
      [Interessent::class, 'medkoeberPostby'],
      // Landinspektoer.php:80:   * @ORM\ManyToOne(targetEntity="Postby")
      [Landinspektoer::class, 'postby'],
      // Salgshistorik.php:234:   * @ORM\ManyToOne(targetEntity="Postby")
      [Salgshistorik::class, 'koeberPostby'],
      // Salgshistorik.php:254:   * @ORM\ManyToOne(targetEntity="Postby")
      [Salgshistorik::class, 'medkoeberPostby'],
      // Salgsomraade.php:152:   * @ORM\ManyToOne(targetEntity="Postby")
      [Salgsomraade::class, 'postby'],
    ]);
  }

  private function canDeleteLokalplan($entity) {
    return $this->canDeleteEntity($entity, [
      // Delomraade.php:104:   * @ORM\ManyToOne(targetEntity="Lokalplan")
      [Delomraade::class, 'lokalplan'],
      // Opkoeb.php:98:   * @ORM\ManyToOne(targetEntity="Lokalplan")
      [Opkoeb::class, 'lokalplan'],
      // Salgsomraade.php:142:   * @ORM\ManyToOne(targetEntity="Lokalplan")
      [Salgsomraade::class, 'lokalplan'],
    ]);
  }

  private function canDeleteLandinspektoer($entity){
    return $this->canDeleteEntity($entity, [
      // Grund.php:364:   * @ORM\ManyToOne(targetEntity="Landinspektoer")
      [Grund::class, 'landinspektoer'],
      // Salgsomraade.php:122:   * @ORM\ManyToOne(targetEntity="Landinspektoer")
      [Salgsomraade::class, 'landinspektoer'],
    ]);
  }

  private function canDeleteLokalsamfund($entity) {
    return $this->canDeleteEntity($entity, [
      // Grund.php:569:   * @ORM\ManyToOne(targetEntity="Lokalsamfund")
      [Grund::class, 'lokalsamfund'],
      // Lokalplan.php:56:   * @ORM\ManyToOne(targetEntity="Lokalsamfund")
      [Lokalplan::class, 'lokalsamfund'],
    ]);
  }

  private function canDeleteSalgsomraade(Salgsomraade $entity) {
    return $this->canDeleteEntity($entity, [
      // Grund.php:579:   * @ORM\ManyToOne(targetEntity="Salgsomraade")
      [Grund::class, 'salgsomraade'],
    ]);
  }

  private function canDeleteKeyword($entity) {
    return $this->canDeleteEntity($entity, [
      // KeywordValue.php:41:   * @ORM\ManyToOne(targetEntity="Keyword")
      [KeywordValue::class, 'keyword'],
    ]);
  }

  private function canDeleteGrund($entity) {
    return $this->canDeleteEntity($entity, [
      // Reservation.php:34:   * @ORM\ManyToOne(targetEntity="Grund")
      [Reservation::class, 'grund'],
      // Salgshistorik.php:244:   * @ORM\ManyToOne(targetEntity="Grund")
      [Salgshistorik::class, 'grund'],
    ]);
  }

  private function canDeleteDelomraade($entity) {
    return $this->canDeleteEntity($entity, [
      // Salgsomraade.php:132:   * @ORM\ManyToOne(targetEntity="Delomraade")
      [Salgsomraade::class, 'delomraade'],
    ]);
  }

  private function canDeleteInteressent($entity) {
    return $this->canDeleteEntity($entity, [
      // Reservation.php:44:   * @ORM\ManyToOne(targetEntity="Interessent")
      [Reservation::class, 'interessent'],
    ]);
  }

  private function canDeleteEntity($entity, array $associations) {
    foreach ($associations as $association) {
      list($sourceClass, $associationName) = $association;
      $queryBuilder = $this->entityManager->getRepository(get_class($entity))->createQueryBuilder('e');
      $queryBuilder
        ->select($queryBuilder->expr()->count('e.id'))
        ->from($sourceClass, 'r')
        ->where('r.' . $associationName . ' = :target')
        ->setParameter('target', $entity);
      $count = (int) $queryBuilder->getQuery()->getSingleScalarResult();

      if ($count > 0) {
        return [
          'message' => '"%name%" is referenced by %count% entities',
          'arguments' => [
            '%class%' => get_class($entity),
            '%name%' => (string) $entity,
            '%count%' => $count,
          ],
        ];
      }
    }

    return TRUE;
  }

}
