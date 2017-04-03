<?php

use AppBundle\Entity\Tag;
use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Exception\ExpectationException;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Exception\TableNotFoundException;
use Doctrine\ORM\Tools\SchemaTool;
use AppBundle\Entity\User;
use AppBundle\Entity\Group;
use Behatch\Context\BaseContext;
use Behatch\Json\Json;
use SebastianBergmann\Diff\Differ;
use Symfony\Component\HttpKernel\KernelInterface;
use Behatch\HttpCall\Request;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Defines Authetication features
 */
class AuthenticationContext extends BaseContext implements Context, KernelAwareContext
{
  private $kernel;
  private $container;

  public function setKernel(KernelInterface $kernel)
  {
    $this->kernel = $kernel;
    $this->container = $this->kernel->getContainer();
  }

  /**
   * @var ManagerRegistry
   */
  private $doctrine;

  /**
   * @var \Doctrine\Common\Persistence\ObjectManager
   */
  private $manager;

  private $request;

  /**
   * Initializes context.
   *
   * Every scenario gets its own context instance.
   * You can also pass arbitrary arguments to the
   * context constructor through behat.yml.
   */
  public function __construct(ManagerRegistry $doctrine, Request $request)
  {
    $this->doctrine = $doctrine;
    $this->manager = $doctrine->getManager();
    $this->schemaTool = new SchemaTool($this->manager);
    $this->classes = $this->manager->getMetadataFactory()->getAllMetadata();
    $this->request = $request;
  }

  /** @AfterScenario */
  public function signOut(AfterScenarioScope $scope) {
    $this->removeAuthenticationHeader();
  }

  /**
   * @When I sign in with username :username and password :password
   */
  public function iSignInWithUsernameAndPassword($username, $password)
  {
    $user = $this->getUser($username);

    if ($user) {
      $encoder_service = $this->container->get('security.encoder_factory');
      $encoder = $encoder_service->getEncoder($user);
      if ($encoder->isPasswordValid($user->getPassword(), $password, $user->getSalt())) {
        $this->addAuthenticationHeader($user);
      }
    } else {
      $this->removeAuthenticationHeader();
    }
  }

  /**
   * @When I authenticate as :username
   */
  public function iAuthenticateAs($username)
  {
    $user = $this->getUser($username);
    if ($user) {
      $this->addAuthenticationHeader($user);
    } else {
      $this->removeAuthenticationHeader();
    }
  }

  /**
   * Get a user by username.
   *
   * @param $username
   * @return User|null
   */
  private function getUser($username)
  {
    $repository = $this->manager->getRepository(User::class);
    return $repository->findOneBy(['username' => $username]);
  }

  /**
   * Add authentication header to request.
   */
  private function addAuthenticationHeader(User $user) {
    // @see https://github.com/Behat/Behat/issues/901
    $token = $this->container->get('lexik_jwt_authentication.encoder')
           ->encode(['username' => $user->getUsername()]);

    $this->request->setHttpHeader('Authorization', 'Bearer ' . $token);
  }

  private function removeAuthenticationHeader() {
    $this->request->setHttpHeader('Authorization', '');
  }

}
