<?php

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
use CrEOF\Spatial\Exception\InvalidValueException;
use CrEOF\Spatial\PHP\Types\Geography\GeographyInterface;
use CrEOF\Geo\WKT\Parser as WKTStringParser;
use Symfony\Component\PropertyAccess\PropertyAccess;


/**
 * Defines user features
 */
class DelomraadeContext extends BaseContext implements Context, KernelAwareContext
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
   * @Given the following delomraader exist:
   */
  public function theFollowingDelomraaderExist(TableNode $table)
  {

    $rows = $table->getHash();
    $count = count($rows);

    $generator = \Faker\Factory::create('da_DK');
    $generator->addProvider(new \AppBundle\Faker\Provider\Grund($generator));
    $populator = new Faker\ORM\Doctrine\Populator($generator, $this->manager);
    $populator->addEntity('AppBundle\Entity\Lokalsamfund', 10);
    $populator->addEntity('AppBundle\Entity\Lokalplan', 10);
    $populator->addEntity('AppBundle\Entity\Landinspektoer', 10);
    $populator->addEntity('AppBundle\Entity\Delomraade', 10);

    $populator->execute();

    $accessor = PropertyAccess::createPropertyAccessor();

    for ($i = 0; $i < $count; $i++) {
      $row = $rows[$i];
      $grund = $this->manager->getRepository('AppBundle:Delomraade')->find($i + 1);

      foreach ($row as $field => $value) {

        switch ($field) {
          default:
            $accessor->setValue($grund, $field, $value);
        }

      }

    }

    $this->manager->flush();
  }
}
