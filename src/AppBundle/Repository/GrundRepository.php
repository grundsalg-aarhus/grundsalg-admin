<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class GrundRepository extends EntityRepository
{

  /**
   * Get Grunde active for sale, select only public fields
   *
   * @return array
   */
  public function getActiveSales()
  {
    return $this->getEntityManager()
      ->createQuery(
        "SELECT g.id, g.vej, g.husnummer, g.bogstav, g.status, g.areal, g.pris  
          FROM AppBundle:Grund g 
          WHERE g.annonceresej IS NULL AND g.datoannonce < CURRENT_TIMESTAMP() AND g.status = 'Ledig'
          ORDER BY g.id ASC"
      )
      ->getResult();
  }
}