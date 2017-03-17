<?php

namespace AppBundle\Controller;

use JavierEguiluz\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;

class EasyAdminController extends BaseAdminController {

  /**
   * Creates Query Builder instance for all the records.
   *
   * Modified to:
   * - Enable sorting on relations of 1+N depth
   *
   * @param string $entityClass
   * @param string $sortDirection
   * @param string|null $sortField
   * @param string|null $dqlFilter
   *
   * @return QueryBuilder The Query Builder instance
   */
  protected function createListQueryBuilder($entityClass, $sortDirection, $sortField = NULL, $dqlFilter = NULL) {
    $queryBuilder = parent::createListQueryBuilder($entityClass, $sortDirection, $sortField, $dqlFilter);

    // We only care about the sortfield
    if (!empty($sortField)) {

      // Split the sortfield by '.' to get relations
      $joinParts = explode('.', $sortField);
      $countParts = count($joinParts);

      // Parent qurebuilder support joins one level deep. We only need to alterthe joins for deeper relations
      if ($countParts > 2) {
        // To sort on fields in related entities we need to build our own joins
        $queryBuilder->resetDQLPart('join');

        // 'Entity' is name for the base entity used in the parent builder
        $entityDqlName = 'entity';
        $fieldDqlName = $sortField;

        // We don't know the depth of the relations, so loop through the parts and add joins for all
        // The last element is the fieldname, so stop at count - 1
        for ($i = 0; $i < $countParts - 1; $i++) {

          if ($i === 0) {
            // This is a join on the base entity
            $queryBuilder->leftJoin('entity.' . $joinParts[$i], $joinParts[$i]);
          }
          else {
            // Where joining on joins
            $queryBuilder->leftJoin($joinParts[$i - 1] . '.' . $joinParts[$i], $joinParts[$i]);
          }

          // Setting the DQL names for use in the 'orderBy' clauses
          $entityDqlName = $joinParts[$i];
          $fieldDqlName = $joinParts[$i + 1];

        }

        $queryBuilder->resetDQLPart('orderBy');
        $queryBuilder->orderBy($entityDqlName . '.' . $fieldDqlName, $sortDirection ?: 'DESC');

      }
    }

    return $queryBuilder;
  }


  /**
   * Creates Query Builder instance for search query.
   *
   * Modified to:
   * - Exclude fields not used in list view
   * - Include listed fields from relations
   *
   * @param string $entityClass
   * @param string $searchQuery
   * @param array $searchableFields
   * @param string|null $sortField
   * @param string|null $sortDirection
   * @param string|null $dqlFilter
   *
   * @return QueryBuilder The Query Builder instance
   */
  protected function createSearchQueryBuilder($entityClass, $searchQuery, array $searchableFields, $sortField = NULL, $sortDirection = NULL, $dqlFilter = NULL) {
    // 'search' contains all searchable fields - excluding relations
    // 'list' contains all list fields - including relations and virtual (non-searchable) fields
    //
    // We only want to search in fields shown in list view.
    // Filter on fields that are in both 'list' and 'search' to exclude non-searchable fields.
    $this->entity['search']['fields'] = array_filter($this->entity['search']['fields'], function ($key) {
      return isset($this->entity['list']['fields'][$key]);
    }, ARRAY_FILTER_USE_KEY);

    // Get the deafult query based on the list fields (- but missing relations)
    $queryBuilder = parent::createSearchQueryBuilder($entityClass, $searchQuery, $searchableFields, $sortField, $sortDirection, $dqlFilter);

    // To search in fields in related entities we need to build our own joins and where
    $queryBuilder->resetDQLPart('join');
    $queryBuilder->resetDQLPart('where');

    // Maintain a list of added joins to avoid adding duplicates
    $joins = array();

    // Loop through list fields to add fields in relations
    $queryParameters = array();
    foreach ($this->entity['list']['fields'] as $name => $metadata) {

      // Split the field name by '.' to get relations
      $joinParts = explode('.', $name);
      $countParts = count($joinParts);

      // 'Entity' is name for the base entity used in the parent builder
      $entityDqlName = 'entity';
      $fieldDqlName = $name;

      // Only process relations
      if ($countParts > 1) {

        // We don't know the depth of the relations, so loop through the parts and add joins for all
        // The last element is the fieldname, so stop at count - 1
        for ($i = 0; $i < $countParts - 1; $i++) {

          // Check if join has been added allready
          if (!in_array($joinParts[$i], $joins)) {
            if ($i === 0) {
              // This is a join on the base entity
              $queryBuilder->leftJoin('entity.' . $joinParts[$i], $joinParts[$i]);
            }
            else {
              // Where joining on joins
              $queryBuilder->leftJoin($joinParts[$i - 1] . '.' . $joinParts[$i], $joinParts[$i]);
            }

            // Remember the added join
            $joins[] = $joinParts[$i];
          }

          // Setting the DQL names for use in the 'where' clauses
          $entityDqlName = $joinParts[$i];
          $fieldDqlName = $joinParts[$i + 1];
        }
      }

      // Add 'where' clauses based on field type - largely identical to logic in parent logic
      $isNumericField = in_array($metadata['dataType'], array(
        'integer',
        'number',
        'smallint',
        'bigint',
        'decimal',
        'float'
      ));
      $isTextField = in_array($metadata['dataType'], array(
        'string',
        'text',
        'guid'
      ));
      $isGuidField = 'guid' === $metadata['dataType'];

      if ($isNumericField && is_numeric($searchQuery)) {
        $queryBuilder->orWhere(sprintf('%s.%s = :exact_query', $entityDqlName, $fieldDqlName));
        // adding '0' turns the string into a numeric value
        $queryBuilder->setParameter('exact_query', 0 + $searchQuery);
      }
      elseif ($isGuidField) {
        // some databases don't support LOWER() on UUID fields
        $queryBuilder->orWhere(sprintf('%s.%s IN (:words_query)', $entityDqlName, $fieldDqlName));
        $queryBuilder->setParameter('words_query', explode(' ', $searchQuery));
      }
      elseif ($isTextField) {
        $searchQuery = mb_strtolower($searchQuery);

        $queryBuilder->orWhere(sprintf('LOWER(%s.%s) LIKE :fuzzy_query', $entityDqlName, $fieldDqlName));
        $queryBuilder->setParameter('fuzzy_query', '%' . $searchQuery . '%');

        $queryBuilder->orWhere(sprintf('LOWER(%s.%s) IN (:words_query)', $entityDqlName, $fieldDqlName));
        $queryBuilder->setParameter('words_query', explode(' ', $searchQuery));
      }

      // Add the field to list of search fields
      $this->entity['search']['fields'][$name] = $metadata;

      // The parent queryBuilder only supports orderBy on direct relations so we reset and rebuild if it's deeper
      if ($countParts > 2 && $name === $sortField) {
        $queryBuilder->resetDQLPart('orderBy');
        $queryBuilder->orderBy($entityDqlName . '.' . $fieldDqlName, $sortDirection ?: 'DESC');
      }
    }

    if (0 !== count($queryParameters)) {
      $queryBuilder->setParameters($queryParameters);
    }

    if (!empty($dqlFilter)) {
      $queryBuilder->andWhere($dqlFilter);
    }

    return $queryBuilder;
  }

}