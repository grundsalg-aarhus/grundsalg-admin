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
class SalgsomraadeContext extends FakerContext implements Context, KernelAwareContext
{
  /**
   * @Given the following salgsomraader exist:
   */
  public function theFollowingSalgsomraaderExist(TableNode $table)
  {

    $rows = $table->getHash();
    $count = count($rows);

    $this->populateWithFaker($count);

    $accessor = PropertyAccess::createPropertyAccessor();

    for ($i = 0; $i < $count; $i++) {
      $row = $rows[$i];
      $salgsomradde = $this->manager->getRepository('AppBundle:Salgsomraade')->find($i + 1);

      foreach ($row as $field => $value) {

        switch ($field) {
          case 'Geometry':
            $salgsomradde->setSpGeometry($this->hydrateWKT($value));
            break;
          case 'Lokalplan':
            $lokalplan = ('null' == $value) ? NULL : $value;
            if($lokalplan) {
              $lokalplan = $this->manager->getRepository('AppBundle:Lokalplan')->find($value);
            }
            $salgsomradde->setLokalplan($lokalplan);
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
