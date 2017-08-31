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
class LokalplanContext extends FakerContext implements Context, KernelAwareContext
{

  /**
   * @Given the following lokalplaner exist:
   */
  public function theFollowingDelomraaderExist(TableNode $table)
  {

    $rows = $table->getHash();
    $count = count($rows);

    $this->populateWithFaker($count);

    $accessor = PropertyAccess::createPropertyAccessor();

    for ($i = 0; $i < $count; $i++) {
      $row = $rows[$i];
      $lokalplan = $this->manager->getRepository('AppBundle:Lokalplan')->find($i + 1);

      foreach ($row as $field => $value) {

        switch ($field) {
          default:
            $accessor->setValue($lokalplan, $field, $value);
        }

      }

    }

    $this->manager->flush();
  }
}
