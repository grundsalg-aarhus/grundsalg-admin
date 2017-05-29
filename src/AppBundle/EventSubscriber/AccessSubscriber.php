<?php

namespace AppBundle\EventSubscriber;

use JavierEguiluz\Bundle\EasyAdminBundle\Configuration\ConfigManager;
use JavierEguiluz\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Form\Extension\Core\DataTransformer\NumberToLocalizedStringTransformer;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class AccessSubscriber implements EventSubscriberInterface {
  /**
   * @var \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface
   */
  protected $authorizationChecker;

  protected $configManager;

  public function __construct(AuthorizationCheckerInterface $authorizationChecker, ConfigManager $configManager)
  {
    $this->authorizationChecker = $authorizationChecker;
    $this->configManager = $configManager;
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
    $roles = $this->getRoles($entity, $action);
    if ($roles !== NULL) {
      $this->requireRole($roles);
    }
  }

protected function getRoles($config, $action) {
  $roles = NULL;

  if (is_object($config)) {
    $config = $this->configManager->getEntityConfigByClass(get_class($config));
  }

  if (is_array($config)) {
    // @TODO: Inherit roles in a meaningful way.
    if (isset($config[$action]['roles'])) {
      $roles = $config[$action]['roles'];
    }
    elseif (isset($config['list']['roles'])) {
      $roles = $config['list']['roles'];
    }
  }

  return $roles;
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
