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
    $salgsomraader = array();

    foreach ($table->getHash() as $row) {
      $grund = new \AppBundle\Entity\Grund();

      if(array_key_exists($row['Salgsomraade'], $salgsomraader)) {
        $salgsomraade = $salgsomraader[$row['Salgsomraade']];
      } else {
        $salgsomraade = new \AppBundle\Entity\Salgsomraade();
        $salgsomraade->setTitel($row['Salgsomraade']);
        $salgsomraade->setNr(1);
        $salgsomraade->setType("test");
        $salgsomraade->setMatrikkel1("M1");
        $salgsomraade->setMatrikkel2("M2");
        $salgsomraade->setEjerlav("Ejerlav1");
        $salgsomraade->setVej("Test");
        $salgsomraade->setGisurl("http://whatever");
        $salgsomraade->setTilsluttet("Tilsluttet");
        $salgsomraade->setSagsnr(4);
        $salgsomraade->setLploebenummer(4);
        $salgsomraade->setCreatedAt(new DateTime());
        $salgsomraade->setUpdatedAt(new DateTime());

        $salgsomraader[$row['Salgsomraade']] = $salgsomraade;
        $this->manager->persist($salgsomraade);
      }

      $grund->setVej($row['Vej']);
      $grund->setHusnummer($row['Husnummer']);
      $grund->setBogstav($row['Bogstav']);

      $polygon = $this->hydrateWKT($row['geometry']);
      $grund->setSpGeometry($polygon);

      $grund->setAnnonceresej($row['AnnonceresEj']);
      $grund->setDatoannonce(new DateTime($row['DatoAnnonce']));

      $grund->setSalgsomraade($salgsomraade);

      $grund->setCreatedAt(new DateTime());
      $grund->setUpdatedAt(new DateTime());

      $this->manager->persist($grund);
    }

    $this->manager->flush();

  }

  private function hydrateWKT($value)
  {
    $parser = new WKTStringParser($value);
    $value  = $parser->parse();

    $typeName  = strtoupper($value['type']);
    $constName = sprintf('CrEOF\Spatial\PHP\Types\Geometry\GeometryInterface::%s', $typeName);

    if (! defined($constName)) {
      throw new InvalidValueException(sprintf('Unsupported Geography type "%s".', $typeName));
    }

    $class = sprintf('CrEOF\Spatial\PHP\Types\Geography\%s', constant($constName));
    if (!class_exists($class)) {
      throw new InvalidValueException(sprintf('Unsupported Geography type "%s".', $typeName));
    }

    return new $class($value['value'], $value['srid']);
  }



}
