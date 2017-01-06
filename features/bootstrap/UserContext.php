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
use Sanpi\Behatch\Context\BaseContext;
use Sanpi\Behatch\Json\Json;
use SebastianBergmann\Diff\Differ;
use Symfony\Component\HttpKernel\KernelInterface;
use Sanpi\Behatch\HttpCall\Request;
use Symfony\Component\PropertyAccess\PropertyAccessor;

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
      $groups = isset($row['groups']) ? preg_split('/\s*,\s*/', $row['groups'], -1, PREG_SPLIT_NO_EMPTY) : [];

      $this->createUser($username, $email, $password, $roles, $groups);
    }
  }

  private function createUser(string $username, string $email, string $password, array $roles, array $groups) {
    $groups = $this->createGroups($groups);

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

    foreach ($groups as $group) {
      $user->addGroup($group);
    }
    $userManager->updateUser($user);
  }

  private function createGroups(array $names, array $roles = null) {
    $groups = new ArrayCollection();
    $repository = $this->manager->getRepository(Group::class);

    foreach ($names as $name) {
      $group = $repository->findOneBy(['name' => $name]);
      if (!$group) {
        $group = new Group($name, $roles ?: []);
        $this->manager->persist($group);
        $this->manager->flush();
      }
      $groups[] = $group;
    }

    return $groups;
  }

}
