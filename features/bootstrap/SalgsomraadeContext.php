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
class SalgsomraadeContext extends BaseContext implements Context, KernelAwareContext
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
   * @Given the following salgsomraader exist:
   */
  public function theFollowingSalgsomraaderExist(TableNode $table)
  {

    $rows = $table->getHash();
    $count = count($rows);

    $generator = \Faker\Factory::create('da_DK');
    $generator->addProvider(new \AppBundle\Faker\Provider\Grund($generator));
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
    $populator->execute();

    $accessor = PropertyAccess::createPropertyAccessor();

    for ($i = 0; $i < $count; $i++) {
      $row = $rows[$i];
      $salgsomradde = $this->manager->getRepository('AppBundle:Salgsomraade')->find($i + 1);

      foreach ($row as $field => $value) {

        switch ($field) {
          case 'Geometry':
            $salgsomradde->setSpGeometry($this->hydrateWKT($value));
            break;
          default:
            $accessor->setValue($salgsomradde, $field, $value);
        }

      }

      if (!$salgsomradde->getSpGeometry()) {
        $salgsomradde->setSpGeometry($this->hydrateWKT());
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
  private function hydrateWKT(string $wktString = 'POINT(577481.3338018466 6233798.938254305),25832')
  {
    $parser = new WKTStringParser($wktString);
    $wktString  = $parser->parse();

    $typeName  = strtoupper($wktString['type']);
    $constName = sprintf('CrEOF\Spatial\PHP\Types\Geometry\GeometryInterface::%s', $typeName);

    if (! defined($constName)) {
      throw new InvalidValueException(sprintf('Unsupported Geography type "%s".', $typeName));
    }

    $class = sprintf('CrEOF\Spatial\PHP\Types\Geometry\%s', constant($constName));
    if (!class_exists($class)) {
      throw new InvalidValueException(sprintf('Unsupported Geography type "%s".', $typeName));
    }

    return new $class($wktString['value'], $wktString['srid']);
  }


}
