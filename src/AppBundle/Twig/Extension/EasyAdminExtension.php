<?php

namespace AppBundle\Twig\Extension;

use AppBundle\Service\IntegrityManager;
use AppBundle\Entity\Event;
use AppBundle\Entity\User;
use AppBundle\Security\Authorization\Voter\EditVoter;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class TwigExtension.
 *
 * @package AdminBundle\Twig\Extension
 */
class EasyAdminExtension extends \Twig_Extension {
  /**
   * @var TokenStorageInterface
   */
  private $tokenStorage;

  /**
   * @var \AppBundle\Security\Authorization\Voter\EditVoter
   */
  private $accessDecisionManager;

  /**
   * @var \Symfony\Component\CssSelector\XPath\TranslatorInterface
   */
  private $translator;

  /**
   * @var IntegrityManager
   */
  private $integrityManager;

  public function __construct(TokenStorageInterface $tokenStorage, AccessDecisionManagerInterface $accessDecisionManager, TranslatorInterface $translator, IntegrityManager $integrityManager) {
    $this->tokenStorage = $tokenStorage;
    $this->accessDecisionManager = $accessDecisionManager;
    $this->translator = $translator;
    $this->integrityManager = $integrityManager;
  }

  /**
   *
   */
  public function getFunctions() {
    return [
      new \Twig_Function('can_perform_action', [$this, 'canPerformAction'], ['is_safe' => ['all']]),
      new \Twig_Function('can_delete', [$this, 'canDelete'], ['is_safe' => ['all']]),
      new \Twig_Function('get_cannot_delete_info', [$this, 'getCannotDeleteInfo'], ['is_safe' => ['all']]),
    ];
  }

  public function canPerformAction($action, $subject) {
    $token = $this->tokenStorage->getToken();
    if (!$token) {
      return FALSE;
    }
    if ($subject instanceof Event) {
      return $this->canPerformActionOnEvent($action, $subject, $token);
    }

    return TRUE;
  }

  private function canPerformActionOnEvent($action, Event $event, TokenInterface $token) {
    switch ($action) {
      case 'clone':
      case 'edit':
        $action = EditVoter::UPDATE;
        break;

      case 'delete':
        $action = EditVoter::REMOVE;
        break;

      case 'show':
        return TRUE;
    }

    return $this->accessDecisionManager->decide($token, [$action], $event);
  }

  public function canDelete($entity) {
    return $this->integrityManager->canDelete($entity) === TRUE;
  }

  public function getCannotDeleteInfo($entity) {
    $info = $this->integrityManager->canDelete($entity);
    return is_array($info) ? $info : NULL;
  }

}
