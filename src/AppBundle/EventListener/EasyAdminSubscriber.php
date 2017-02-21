<?php

namespace AppBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use JavierEguiluz\Bundle\EasyAdminBundle\Event\EasyAdminEvents;

class EasyAdminSubscriber implements EventSubscriberInterface
{

  public static function getSubscribedEvents()
  {
    return array(
      EasyAdminEvents::PRE_LIST => array('setListFieldsSortable'),
    );
  }

  public function setListFieldsSortable(GenericEvent $event)
  {
    $entity = $event->getArgument('entity');

    if (!($entity['controller'] === 'AppBundle\Controller\GrundController')) {
      return;
    }

    $entity['list']['fields']['postby.postalcode']['virtual'] = false;
    $entity['list']['fields']['postby.postalcode']['sortable'] = true;

    $event->setArgument('entity', $entity);
  }
}