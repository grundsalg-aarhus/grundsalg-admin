<?php

namespace AppBundle\Configuration;

use JavierEguiluz\Bundle\EasyAdminBundle\Configuration\ConfigManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class EasyAdminConfigManager extends ConfigManager {
  /**
   * @var \AppBundle\Configuration\AuthorizationCheckerInterface
   */
  protected $authorizationChecker;

  public function __construct(ContainerInterface $container, AuthorizationCheckerInterface $authorizationChecker) {
    parent::__construct($container);
    $this->authorizationChecker = $authorizationChecker;
  }

  public function getBackendConfig($propertyPath = NULL) {
    $config = parent::getBackendConfig($propertyPath);

    if ($propertyPath === 'design.menu') {
      $config = self::arrayFilterRecursive($config, function ($item) {
        if (!isset($item['roles'])) {
          return TRUE;
        }

        $roles = $item['roles'];
        if (!is_array($roles)) {
          $roles = [$roles];
        }

        return $this->hasRole($roles);
      });

      $this->reindexMenu($config);
    }

    return $config;
  }

  private function reindexMenu(array &$config, $menuIndex = NULL) {
    $config = array_values($config);
    foreach ($config as $index => &$item) {
      if ($menuIndex === NULL) {
        $item['menu_index'] = $index;
        if (isset($item['children'])) {
          $this->reindexMenu($item['children'], $index);
        }
      } else {
        $item['menu_index'] = $menuIndex;
        $item['submenu_index'] = $index;
      }
    }
  }

  private function hasRole(array $roleNames) {
    foreach ($roleNames as $roleName) {
      if ($this->authorizationChecker->isGranted($roleName)) {
        return TRUE;
      }
    }

    return FALSE;
  }

  // @see http://php.net/manual/en/function.array-filter.php#87581
  private static function arrayFilterRecursive(array $input, callable $callback) {
    foreach ($input as &$value) {
      if (is_array($value)) {
        $value = self::arrayFilterRecursive($value, $callback);
      }
    }

    return array_filter($input, $callback);
  }
}
