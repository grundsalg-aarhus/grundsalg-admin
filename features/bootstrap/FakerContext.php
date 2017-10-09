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
class FakerContext extends BaseContext implements Context, KernelAwareContext
{
  protected $kernel;
  protected $container;
  protected $doctrine;
  protected $manager;
  protected $request;

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

  public function setKernel(KernelInterface $kernel)
  {
    $this->kernel = $kernel;
    $this->container = $this->kernel->getContainer();
  }

  public function populateWithFaker(int $numberOfEntities)
  {
    $generator = \Faker\Factory::create('da_DK');
    $generator->addProvider(new \AppBundle\Faker\Provider\Grund($generator));
    $generator->addProvider(new \AppBundle\Faker\Provider\Delomraade($generator));
    $generator->addProvider(new \AppBundle\Faker\Provider\Lokalplan($generator));

    $populator = new Faker\ORM\Doctrine\Populator($generator, $this->manager);
    $populator->addEntity('AppBundle\Entity\Lokalsamfund', 10);
    $populator->addEntity('AppBundle\Entity\Lokalplan', 10, array(
      'nr' => function() use ($generator) { return $generator->nr(); },
      'forbrugsandel' => function() use ($generator) { return $generator->forbrugsandel(); },
    ));
    $populator->addEntity('AppBundle\Entity\Landinspektoer', 10);
    $populator->addEntity('AppBundle\Entity\Delomraade', 10, array(
      'kpl4' => function() use ($generator) { return $generator->kpl4(); },
    ));
    $populator->addEntity('AppBundle\Entity\Salgsomraade', 10, array(
      'type' => function() use ($generator) { return $generator->type(); },
    ));
    $populator->addEntity('AppBundle\Entity\Grund', $numberOfEntities, array(
      'type' => function() use ($generator) { return $generator->type(); },
      'salgsType' => function() use ($generator) { return $generator->salgsType(); },
      'maxetagem2' => function() use ($generator) { return $generator->maxetagem2(); },
      'areal' => function() use ($generator) { return $generator->areal(); },
      'arealvej' => function() use ($generator) { return $generator->arealvej(); },
      'arealkotelet' => function() use ($generator) { return $generator->arealkotelet(); },
      'bruttoareal' => function() use ($generator) { return $generator->bruttoareal(); },
      'prism2' => function() use ($generator) { return $generator->prism2(); },
      'prisfoerkorrektion' => function() use ($generator) { return $generator->prisfoerkorrektion(); },
      'akrkorr1' => function() use ($generator) { return $generator->korr1(); },
      'akrkorr2' => function() use ($generator) { return $generator->korr1(); },
      'akrkorr3' => function() use ($generator) { return $generator->korr1(); },
      'priskoor1' => function() use ($generator) { return $generator->korr1(); },
      'priskoor2' => function() use ($generator) { return $generator->korr1(); },
      'priskoor3' => function() use ($generator) { return $generator->korr1(); },
      'pris' => function() use ($generator) { return $generator->pris(); },
      'fastpris' => function() use ($generator) { return $generator->pris(); },
      'minbud' => function() use ($generator) { return $generator->pris(); },
      'salgsprisumoms' => function() use ($generator) { return $generator->pris(); },
      'accept' => null,
      'antagetbud' => null,
      'auktionslutdato' => null,
      'auktionstartdato' => null,
      'beloebanvist' => null,
      'datoannonce' => null,
      'datoannonce1' => null,
      'overtagelse' => null,
      'resslut' => null,
      'resstart' => null,
      'skoederekv' => null,
      'tilbudslut' => null,
      'tilbudstart' => null,
    ));
    $populator->execute();
  }

}
