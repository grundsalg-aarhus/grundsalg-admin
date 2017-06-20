<?php
/**
 * Created by PhpStorm.
 * User: turegjorup
 * Date: 17/05/2017
 * Time: 15.37
 */

namespace AppBundle\EventSubscriber;

use AppBundle\Entity\Grund;
use AppBundle\Entity\GrundCollection;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class GrundBulkCreateSubscriber implements EventSubscriberInterface {

  private $em;

  public function __construct(EntityManager $entityManager)
  {
    $this->em = $entityManager;
  }

  public static function getSubscribedEvents()
  {
    return array(
      'easy_admin.pre_persist' => array('bulkCreateGrund'),
      'easy_admin.post_persist' => array('collectionCleanup'),
    );
  }

  /**
   * When a 'grund' collection is saved, loop through the collection items and
   * copy the values set on the collection level to the individual items.
   *
   * @param \Symfony\Component\EventDispatcher\GenericEvent $event
   */
  public function bulkCreateGrund(GenericEvent $event)
  {
    $collection = $event->getSubject();

    if (!($collection instanceof GrundCollection)) {
      return;
    }

    foreach ($collection->getGrunde() as $grund) {

      $grund->setSalgsomraade($collection->getSalgsomraade());

      $grund->setType($collection->getType());
      $grund->setEjerlav($collection->getEjerlav());
      $grund->setVej($collection->getVej());
      $grund->setPostby($collection->getPostby());
      $grund->setLokalsamfund($collection->getLokalsamfund());
      $grund->setLandinspektoer($collection->getLandinspektoer());
      $grund->setPrism2($collection->getPrism2());

      $grund->setSalgstype($collection->getSalgstype());
      $grund->setAnnonceres($collection->isAnnonceres());
      $grund->setDatoannonce($collection->getDatoannonce());
      $grund->setDatoannonce1($collection->getDatoannonce1());
      $grund->setTilsluttet($collection->getTilsluttet());

      $this->em->persist($grund);
    }

    $this->em->flush();
  }

  /**
   * 'Grund collection' is a hack to support 'multiple create' forms for 'Grund' through EasyAdmins
   * standard config and functions. We only need the individual 'grunde' to be persisted after which
   * the collection becomes redundant so we delete it.
   *
   * @param \Symfony\Component\EventDispatcher\GenericEvent $event
   */
  public function collectionCleanup(GenericEvent $event) {
    $collection = $event->getSubject();

    if (!($collection instanceof GrundCollection)) {
      return;
    }

    $this->em->remove($collection);
    $this->em->flush();
  }

}