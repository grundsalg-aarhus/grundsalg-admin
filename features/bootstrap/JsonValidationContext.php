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
 * Defines JSON validation features
 */
class JsonValidationContext extends BaseContext implements Context
{
  private $request;

  /**
   * Initializes context.
   *
   * Every scenario gets its own context instance.
   * You can also pass arbitrary arguments to the
   * context constructor through behat.yml.
   */
  public function __construct(Request $request)
  {
    $this->request = $request;
  }

  /**
   * @Then the JSON should not differ from:
   */
  public function theJsonShouldNotDifferFrom(PyStringNode $content) {
    $actual = $this->getJson();

    try {
      $expected = new Json($content);
    }
    catch (\Exception $e) {
      throw new \Exception('The expected JSON is not a valid');
    }

    try {
      $this->assertSame(
        (string)$expected,
        (string)$actual,
        "The json is equal to:\n" . $actual->encode()
      );
    } catch (ExpectationException $ex) {
      $differ = new Differ("--- Expected\n+++ Actual\n", true);
      $message = $differ->diff($expected->encode(), $actual->encode());
      throw new ExpectationException($message, $this->getSession(), $ex);
    }
  }

  protected function getJson()
  {
    return new Json($this->request->getContent());
  }

}
