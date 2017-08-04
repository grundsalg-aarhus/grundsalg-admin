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
class GrundContext extends BaseContext implements Context, KernelAwareContext
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
   * @Given the following grunde exist:
   */
  public function theFollowingGrundeExist(TableNode $table)
  {

    $rows = $table->getHash();
    $count = count($rows);

    $generator = \Faker\Factory::create('da_DK');
    $generator->addProvider(new \AppBundle\Faker\Provider\Grund($generator));
    $generator->addProvider(new \AppBundle\Faker\Provider\Lokalplan($generator));

    $populator = new Faker\ORM\Doctrine\Populator($generator, $this->manager);

    $populator->addEntity('AppBundle\Entity\Lokalsamfund', 10);
    $populator->addEntity('AppBundle\Entity\Lokalplan', 10, array(
      'nr' => function() use ($generator) { return $generator->nr(); },
    ));
    $populator->addEntity('AppBundle\Entity\Landinspektoer', 10);
    $populator->addEntity('AppBundle\Entity\Delomraade', 10);
    $populator->addEntity('AppBundle\Entity\Salgsomraade', 10, array(
      'type' => function() use ($generator) { return $generator->type(); },
    ));
    $populator->addEntity('AppBundle\Entity\Grund', $count, array(
      'type' => function() use ($generator) { return $generator->type(); },
      'salgsType' => function() use ($generator) { return $generator->salgsType(); },
      'accept' => null,
      'annonceres' => null,
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

    $accessor = PropertyAccess::createPropertyAccessor();

    for ($i = 0; $i < $count; $i++) {
      $row = $rows[$i];
      $grund = $this->manager->getRepository('AppBundle:Grund')->find($i + 1);

      foreach ($row as $field => $value) {

        switch ($field) {
          case 'DatoAnnonce':
            $value = new DateTime($row['DatoAnnonce']);
            $accessor->setValue($grund, $field, $value);
            break;
          case 'auktionstartdato':
            $value = new DateTime($row['auktionstartdato']);
            $accessor->setValue($grund, $field, $value);
            break;
          case 'auktionslutdato':
            $value = new DateTime($row['auktionslutdato']);
            $accessor->setValue($grund, $field, $value);
            break;
          case 'Salgsomraade':
            $value = $this->manager->getRepository('AppBundle:Salgsomraade')->find($value);
            $accessor->setValue($grund, $field, $value);
            break;
          case 'Geometry':
            $grund->setSpGeometry($this->hydrateWKT($value));
            break;
          default:
            $accessor->setValue($grund, $field, $value);
        }

      }

      if (!$grund->getSpGeometry()) {
        $grund->setSpGeometry($this->hydrateWKT());
      }

    }

    $this->manager->flush();
  }

  /**
   * Get a geometry object from a WKT string
   *
   * @param $wktString
   * @return mixed
   * @throws InvalidValueException
   */
  private function hydrateWKT(string $wktString = 'POLYGON((561503.4602595221 6222216.785767948,561656.8824384945 6222220.055102484,561656.5690964112 6222266.055739188,561646.6081165016 6222272.61440419,561503.1799008161 6222269.555026918,561503.4602595221 6222216.785767948)),25832')
  {
    $parser = new WKTStringParser($wktString);
    $wktString  = $parser->parse();

    $typeName  = strtoupper($wktString['type']);
    $constName = sprintf('CrEOF\Spatial\PHP\Types\Geometry\GeometryInterface::%s', $typeName);

    if (! defined($constName)) {
      throw new InvalidValueException(sprintf('Unsupported Geography type "%s".', $typeName));
    }

    $class = sprintf('CrEOF\Spatial\PHP\Types\Geography\%s', constant($constName));
    if (!class_exists($class)) {
      throw new InvalidValueException(sprintf('Unsupported Geography type "%s".', $typeName));
    }

    return new $class($wktString['value'], $wktString['srid']);
  }



}
