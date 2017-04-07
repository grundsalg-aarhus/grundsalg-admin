<?php

namespace AppBundle\EventSubscriber;

use JavierEguiluz\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class AccessSubscriber implements EventSubscriberInterface {
  /**
   * @var \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface
   */
  protected $authorizationChecker;

  public function __construct(AuthorizationCheckerInterface $authorizationChecker)
  {
    $this->authorizationChecker = $authorizationChecker;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      EasyAdminEvents::PRE_NEW => ['checkAccess'],
      EasyAdminEvents::PRE_LIST => ['checkAccess'],
      EasyAdminEvents::PRE_EDIT => ['checkAccess'],
      EasyAdminEvents::PRE_SHOW => ['checkAccess'],
      EasyAdminEvents::PRE_REMOVE => ['checkAccess'],
    ];
  }

  public function checkAccess(GenericEvent $event)
  {
    $request = $event->getArgument('request');
    $action = $request->query->get('action', 'list');
    $entity = $event->getArgument('entity');
    if (isset($entity[$action]['roles'])) {
      $roles = $entity[$action]['roles'];
      $this->requireRole($roles);
    }
  }

  public function requireRole(array $roleNames) {
    foreach ($roleNames as $roleName) {
      if ($this->authorizationChecker->isGranted($roleName)) {
        return;
      }
    }

    throw new AccessDeniedHttpException(sprintf('Role %s required', implode(', ', $roleNames)));
  }
}
