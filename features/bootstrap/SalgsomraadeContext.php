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
use Sanpi\Behatch\Context\BaseContext;
use Sanpi\Behatch\Json\Json;
use SebastianBergmann\Diff\Differ;
use Symfony\Component\HttpKernel\KernelInterface;
use Sanpi\Behatch\HttpCall\Request;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use CrEOF\Spatial\Exception\InvalidValueException;
use CrEOF\Spatial\PHP\Types\Geography\GeographyInterface;
use CrEOF\Geo\WKT\Parser as WKTStringParser;

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

    $postBy = new \AppBundle\Entity\Postby();
    $postBy->setCity('Malling');
    $postBy->setPostalcode(8340);
    $postBy->setCreatedAt(new DateTime());
    $postBy->setUpdatedAt(new DateTime());

    $this->manager->persist($postBy);

    foreach ($table->getHash() as $row) {

//      $data = [
//        'id' => $area->getId(),
//        'type' => $area->getType(),
//        'title' => $area->getTitel(),
//        'vej' => $area->getVej(),
//        'city' => $area->getPostby() ? $area->getPostby()->getCity() : null,
//        'postalCode' => $area->getPostby() ? $area->getPostby()->getPostalcode() : null,
//        'geometry' => $area->getSpGeometryArray(),
//        'srid' => $area->getSrid(),
//      ];

      $salgsomraade = new \AppBundle\Entity\Salgsomraade();
      $salgsomraade->setType($row['Type']);
      $salgsomraade->setTitel($row['Titel']);
      $salgsomraade->setVej($row['Vej']);
      $salgsomraade->setAnnonceres($row['Annonceres']);
      $salgsomraade->setPostby($postBy);

      // Defaults
      $salgsomraade->setNr(1);
      $salgsomraade->setLploebenummer(1);

      // @TODO Add geomatry to omraade
//      $point = $this->hydrateWKT($row['geometry'], $row['srid']);
//      $salgsomraade->setSpGeometry($point);
//      $salgsomraade->setSrid($row['srid']);

      $salgsomraade->setCreatedAt(new DateTime());
      $salgsomraade->setUpdatedAt(new DateTime());

      $this->manager->persist($salgsomraade);

    }

    $this->manager->flush();

  }

  private function hydrateWKT($value, $srid)
  {
    $parser = new WKTStringParser($value);
    $value = $parser->parse();
    $value['srid'] = $srid;

    $typeName = strtoupper($value['type']);
    $constName = sprintf('CrEOF\Spatial\PHP\Types\Geometry\GeometryInterface::%s', $typeName);

    if (!defined($constName)) {
      throw new InvalidValueException(sprintf('Unsupported Geography type "%s".', $typeName));
    }

    $class = sprintf('CrEOF\Spatial\PHP\Types\Geography\%s', constant($constName));
    if (!class_exists($class)) {
      throw new InvalidValueException(sprintf('Unsupported Geography type "%s".', $typeName));
    }

    return new $class($value['value'], $srid);
  }


}
