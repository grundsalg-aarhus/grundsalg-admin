<?php
/**
 * Created by PhpStorm.
 * User: turegjorup
 * Date: 01/09/2017
 * Time: 13.35
 */

namespace Tests\AppBundle\Controller;

use AppBundle\Controller\ApiController;
use AppBundle\DBAL\Types\GrundPublicStatus;
use AppBundle\DBAL\Types\GrundSalgStatus;
use AppBundle\DBAL\Types\GrundStatus;
use AppBundle\Entity\Grund;
use PHPUnit\Framework\TestCase;

class ApiControllerTest extends TestCase {

	/**
	 * GRUND-255:
	 *
	 * Accepteret = solgt
	 * fremtidig / Ledig = Fremtidig
	 * Ledig/Ledig = Ledig
	 * Solgt / Ledig = ledig
	 * Ledig/ reserveret = Reserveret
	 * Ledig/ skøde rekvireret = solgt
	 * Auktion slut / Skøde rekvireret = Solgt
	 * Ledig / Solgt = Solgt
	 * Auktion slut / Solgt = Solgt
	 * annonceret / ledig = i udbud.
	 */
	public function testGetPublicStatus() {

		$grund      = new Grund();
		$controller = new ApiController();

		// status | salgStatus = webstatus

		// Fremtidig | Accepteret = Solgt
		$grund->setStatus( GrundStatus::FREMTIDIG );
		$grund->setSalgstatus( GrundSalgStatus::ACCEPTERET );
		$public = $this->invokeMethod( $controller, 'getPublicStatus', [ $grund ] );
		$this->assertEquals( GrundPublicStatus::SOLGT, $public );

		// Fremtidig | Ledig = Fremtidig
		$grund->setStatus( GrundStatus::FREMTIDIG );
		$grund->setSalgstatus( GrundSalgStatus::LEDIG );
		$public = $this->invokeMethod( $controller, 'getPublicStatus', [ $grund ] );
		$this->assertEquals( GrundPublicStatus::FREMTIDIG, $public );

		// Ledig | Ledig = Ledig
		$grund->setStatus( GrundStatus::LEDIG );
		$grund->setSalgstatus( GrundSalgStatus::LEDIG );
		$public = $this->invokeMethod( $controller, 'getPublicStatus', [ $grund ] );
		$this->assertEquals( GrundPublicStatus::LEDIG, $public );

		// Solgt | Ledig = Ledig
		$grund->setStatus( GrundStatus::SOLGT );
		$grund->setSalgstatus( GrundSalgStatus::LEDIG );
		$public = $this->invokeMethod( $controller, 'getPublicStatus', [ $grund ] );
		$this->assertEquals( GrundPublicStatus::LEDIG, $public );

		// Ledig | Reserveret = Reserveret
		$grund->setStatus( GrundStatus::LEDIG );
		$grund->setSalgstatus( GrundSalgStatus::RESERVERET );
		$public = $this->invokeMethod( $controller, 'getPublicStatus', [ $grund ] );
		$this->assertEquals( GrundPublicStatus::RESERVERET, $public );

		// Ledig | Skøde rekvireret = Solgt
		$grund->setStatus( GrundStatus::LEDIG );
		$grund->setSalgstatus( GrundSalgStatus::SKOEDE_REKVIRERET );
		$public = $this->invokeMethod( $controller, 'getPublicStatus', [ $grund ] );
		$this->assertEquals( GrundPublicStatus::SOLGT, $public );

		// Auktion slut | Skøde rekvireret = Solgt
		$grund->setStatus( GrundStatus::AUKTION_SLUT );
		$grund->setSalgstatus( GrundSalgStatus::SKOEDE_REKVIRERET );
		$public = $this->invokeMethod( $controller, 'getPublicStatus', [ $grund ] );
		$this->assertEquals( GrundPublicStatus::SOLGT, $public );

		// Ledig | Solgt = Solgt
		$grund->setStatus( GrundStatus::LEDIG );
		$grund->setSalgstatus( GrundSalgStatus::SOLGT );
		$public = $this->invokeMethod( $controller, 'getPublicStatus', [ $grund ] );
		$this->assertEquals( GrundPublicStatus::SOLGT, $public );

		// Auktion slut | Solgt = Solgt
		$grund->setStatus( GrundStatus::AUKTION_SLUT );
		$grund->setSalgstatus( GrundSalgStatus::SOLGT );
		$public = $this->invokeMethod( $controller, 'getPublicStatus', [ $grund ] );
		$this->assertEquals( GrundPublicStatus::SOLGT, $public );

		// Annonceret | Ledig = I udbud
		$grund->setStatus( GrundStatus::ANNONCERET );
		$grund->setSalgstatus( GrundSalgStatus::LEDIG );
		$public = $this->invokeMethod( $controller, 'getPublicStatus', [ $grund ] );
		$this->assertEquals( GrundPublicStatus::I_UDBUD, $public );

	}

	/**
	 * Call protected/private method of a class.
	 *
	 * @param object &$object Instantiated object that we will run method on.
	 * @param string $methodName Method name to call
	 * @param array $parameters Array of parameters to pass into method.
	 *
	 * @return mixed Method return.
	 */
	public function invokeMethod( &$object, $methodName, array $parameters = [] ) {
		$reflection = new \ReflectionClass( get_class( $object ) );
		$method     = $reflection->getMethod( $methodName );
		$method->setAccessible( true );

		return $method->invokeArgs( $object, $parameters );
	}

}
