<?php

namespace Xama\Tests\Interfaces;

use Xama\Interfaces\Controller;
use WP_Mock\Tools\TestCase;

/**
 * @covers Controller
 */
class ControllerTest extends TestCase {
	private $controller;

	public function setUp(): void {
		\WP_Mock::setUp();

		$this->controller = $this->getMockForAbstractClass( Controller::class );
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_run() {
		$this->controller->expects( $this->once() )
			->method( 'run' );

		$this->controller->run();

		$this->assertConditionsMet();
	}
}
