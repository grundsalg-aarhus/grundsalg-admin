<?php

namespace AppBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;

class TimestampableSubscriber implements EventSubscriber {
  /**
   * {@inheritdoc}
   */
  public function getSubscribedEvents() {
    return [
      'prePersist',
    ];
  }

  /**
   * Update timestampable data on object if not already set.
   *
   * @param \Doctrine\ORM\Event\LifecycleEventArgs $args
   */
  public function prePersist(LifecycleEventArgs $args)
  {
    $object = $args->getObject();
    if (method_exists($object, 'getCreatedAt') && method_exists($object, 'setCreatedAt')) {
      if (!$object->getCreatedAt()) {
        $object->setCreatedAt(new \DateTime());
      }
    }
    if (method_exists($object, 'getUpdatedAt') && method_exists($object, 'setUpdatedAt')) {
      if (!$object->getUpdatedAt()) {
        $object->setUpdatedAt(new \DateTime());
      }
    }
  }
}
