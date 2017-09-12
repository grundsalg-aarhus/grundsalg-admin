<?php

namespace ITK\DoctrineIntegrityBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

/**
 * Class for deciding if entities can be deleted without violating referential integrety.
 */
class IntegrityManager
{
    /**
     * An entity manager.
     *
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;

    /**
     * Constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Decide if an entity can be deleted without violating referential integrity.
     *
     * @param object $entity
     *   The entity.
     *
     * @return bool|array
     *   Return true iff entity can be safely deleted. Otherwise, return info on entities with references to the entity.
     */
    public function canDelete($entity)
    {
        $entityAssociations = $this->getNonCascadeAssocications($entity);

        // Get references to the entity up for deletion.
        $references = [];
        foreach ($entityAssociations as $className => $associations) {
            foreach ($associations as $association) {
                $fieldName = $association['fieldName'];
                $count     = $this->getNumberOfReferences($entity, $association);
                if ($count > 0) {
                    if ( ! isset($references['total'])) {
                        $references['total']      = 0;
                        $references['references'] = [];
                    }
                    $references['total'] += $count;
                    if ( ! isset($references['references'][$className])) {
                        $references['references'][$className] = [];
                    }
                    $references['references'][$className][$fieldName] = $count;
                }
            }
        }

        return $references ?: true;
    }

    /**
     * Get all associations excluding those with cascade={"remove"} targeting a specified entity class.
     *
     * @param object $entity
     *   The entity.
     *
     * @return array
     *   class name => [ associations ]
     */
    private function getNonCascadeAssocications($entity)
    {
        $allAssociations = [];

        $metadata           = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $entityMetadata     = $this->entityManager->getClassMetadata(get_class($entity));
        $entityAssociations = $entityMetadata->getAssociationMappings();

        foreach ($metadata as $metadatum) {
            $associations = $this->filterTargetEntityAssociations($metadatum, $entity);
            foreach ($associations as $association) {
                if ($this->isIntegrityAssociation($association) && ! $this->isCascadeRemove($association, $entityAssociations)) {
                    $className = $association['sourceEntity'];
                    if ( ! isset($allAssociations[$className])) {
                        $allAssociations[$className] = [];
                    }
                    $allAssociations[$className][] = $association;
                }
            }
        }

        return $allAssociations;
    }

    /**
     * @param ClassMetadata $metadatum
     * @param $entity
     *
     * @return array
     */
    private function filterTargetEntityAssociations(ClassMetadata $metadatum, $entity)
    {
        $associations = array_filter(
            $metadatum->getAssociationMappings(),
            function ($association) use ($entity) {
                return ! isset($association['inherited']) && $association['targetEntity'] === get_class($entity);
            }
        );

        return $associations;
    }

    /**
     * @param $association
     *
     * @return bool
     */
    private function isIntegrityAssociation($association)
    {
        switch ($association['type']) {
            case ClassMetadataInfo::MANY_TO_ONE:
                return true;

            default:
                return false;
        }
    }

    /**
     * @param $association
     * @param $entityAssociations
     *
     * @return bool
     */
    private function isCascadeRemove($association, $entityAssociations)
    {
        if ( ! isset($association['inversedBy']) || (isset($entityAssociations[$association['inversedBy']]) && ! $entityAssociations[$association['inversedBy']]['isCascadeRemove'])) {
            return false;
        }

        return true;
    }

    /**
     * Get number of references from a class property to an entity.
     *
     * @param $entity
     *   The entity.
     * @param $association
     *   The $association.
     *
     * @return int
     *   The number of references to the entity.
     */
    private function getNumberOfReferences($entity, $association)
    {
        if ($association['isOwningSide']) {

            $queryBuilder = $this->entityManager->getRepository($association['sourceEntity'])->createQueryBuilder('e');
            $queryBuilder
                ->select($queryBuilder->expr()->count('e.id'))
                ->where('e.'.$association['fieldName'].' = :target')
                ->setParameter('target', $entity);

        } else {

            $queryBuilder = $this->entityManager->getRepository($association['targetEntity'])->createQueryBuilder('e');
            $queryBuilder
                ->select($queryBuilder->expr()->count('e.id'))
                ->join('e.'.$association['mappedBy'], 'j')
                ->where('e = :target')
                ->setParameter('target', $entity);

        }

        return (int)$queryBuilder->getQuery()->getSingleScalarResult();
    }

}
