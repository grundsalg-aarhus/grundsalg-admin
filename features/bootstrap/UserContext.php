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
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Defines user features
 */
class UserContext extends BaseContext implements Context, KernelAwareContext
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

   /**
   * @Given the following users exist:
   */
  public function theFollowingUsersExist(TableNode $table) {
    foreach ($table->getHash() as $row) {
      $username = $row['username'];
      $email = $username . '@example.com';
      $password = isset($row['password']) ? $row['password'] : uniqid();
      $roles = isset($row['roles']) ? preg_split('/\s*,\s*/', $row['roles'], -1, PREG_SPLIT_NO_EMPTY) : [];

      $this->createUser($username, $email, $password, $roles);
    }
  }

  /**
   * Creates a user with role and logs in
   *
   * @When /^(?:|I )am logged in with role "(?P<role>[^"]+)"$/
   */
  public function iAmLoggedInWithRole($role) {
    $session = $this->container->get('session');
    $client = $this->getSession()->getDriver()->getClient();

    $role = 'ROLE_'.strtoupper($role);

    // the firewall context defaults to the firewall name
    $firewallContext = 'main';

    $token = new UsernamePasswordToken('test', null, $firewallContext, array($role));
    $session->set('_security_'.$firewallContext, serialize($token));
    $session->save();

    $cookie = new Cookie($session->getName(), $session->getId());
    $client->getCookieJar()->set($cookie);
  }

  /**
   * @param string $username
   * @param string $email
   * @param string $password
   * @param array $roles
   */
  private function createUser(string $username, string $email, string $password, array $roles) {
    $userManager = $this->container->get('fos_user.user_manager');

    $user = $userManager->findUserBy(['username' => $username ]);
    if (!$user) {
      $user = $userManager->createUser();
    }
    $user
      ->setEnabled(true)
      ->setUsername($username)
      ->setPlainPassword($password)
      ->setEmail($email)
      ->setRoles($roles);

    $userManager->updateUser($user);
  }

}
