<?php

namespace AppBundle\Controller;

use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;

class GrundController extends BaseAdminController
{

  protected function listAction() {
    $resp = parent::listAction();

    return $resp;
  }

  /**
   * Alter the list query to allow for sort on joined table fields.
   *
   * @param string $entityClass
   * @param string $sortDirection
   * @param string $sortField
   *
   * @return QueryBuilder
   */
  public function createListQueryBuilder($entityClass, $sortDirection, $sortField = NULL, $dqlFilter = NULL)
  {
    $em = $this->getDoctrine()->getManagerForClass($this->entity['class']);
    $queryBuilder = $em->createQueryBuilder()
      ->select('entity')
      ->from($this->entity['class'], 'entity');

    // Split the sortfiled by '.' to get relations
    $joinParts = explode('.', $sortField);
    $countParts = count($joinParts);
    if($countParts > 1) {
      for($i = 0; $i < $countParts - 1; $i++) {
        if($i === 0) {
          // This is a join on the base entity
          $queryBuilder->leftJoin('entity.' . $joinParts[$i], $joinParts[$i]);
        } else {
          // Where joining on joins
          // Naive implementation assumes fieldname and tablename are the same!
          $queryBuilder->leftJoin($joinParts[$i-1] . '.' . $joinParts[$i], $joinParts[$i]);
        }
      }
      $sortField = $joinParts[$countParts-2] . '.' . $joinParts[$countParts-1];
    }

    if (!empty($dqlFilter)) {
      $queryBuilder->andWhere($dqlFilter);
    }
    if (null !== $sortField && $countParts < 2) {
      $queryBuilder->orderBy('entity.' . $sortField, $sortDirection ?: 'DESC');
    } else if (null !== $sortField && count($joinParts) > 1) {
      $queryBuilder->orderBy($sortField, $sortDirection ?: 'DESC');
    }

    return $queryBuilder;
  }

  /**
   * This method overrides the default query builder used to search for this
   * entity. This allows to make a more complex search joining related entities.
   */
  protected function createSearchQueryBuilder($entityClass, $searchQuery, array $searchableFields, $sortField = null, $sortDirection = null, $dqlFilter = null)
  {
    $em = $this->getDoctrine()->getManagerForClass($this->entity['class']);
    $queryBuilder = $em->createQueryBuilder()
      ->select('entity')
      ->from($this->entity['class'], 'entity')
      ->join('entity.postby', 'postby')
      ->join('entity.salgsomraade', 'salgsomraade')
      ->join('salgsomraade.lokalplan', 'lokalplan')
      ->orWhere('LOWER(postby.postalcode) LIKE :query')
      ->orWhere('LOWER(postby.city) LIKE :query')
      ->orWhere('LOWER(salgsomraade.titel) LIKE :query')
      ->orWhere('LOWER(lokalplan.nr) LIKE :query')
      ->setParameter('query', '%' . strtolower($searchQuery) . '%');

    // Split the sortfiled by '.' to get relations
    $joinParts = explode('.', $sortField);
    $countParts = count($joinParts);
    if($countParts > 1) {
      $sortField = $joinParts[$countParts-2] . '.' . $joinParts[$countParts-1];
    }

    if (!empty($dqlFilter)) {
      $queryBuilder->andWhere($dqlFilter);
    }
    if (null !== $sortField && $countParts < 2) {
      $queryBuilder->orderBy('entity.' . $sortField, $sortDirection ?: 'DESC');
    } else if (null !== $sortField && count($joinParts) > 1) {
      $queryBuilder->orderBy($sortField, $sortDirection ?: 'DESC');
    }

    return $queryBuilder;

  }


}