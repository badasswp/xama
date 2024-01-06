<?php

namespace Xama\Tests\Controllers;

use Mockery;
use stdClass;
use Xama\Core\Settings;
use WP_Mock\Tools\TestCase;
use Xama\Controllers\LoginController;

/**
 * @covers LoginController
 */
class LoginControllerTest extends TestCase {
	private $controller;

	public function setUp(): void {
		\WP_Mock::setUp();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_run() {
		$controller = Mockery::mock( LoginController::class )->makePartial();

		$controller->shouldAllowMockingProtectedMethods();
		$controller->shouldReceive( 'validate' )->andReturn( [] );
		$controller->shouldReceive( 'auth_user' )->once();

		$controller->run();

		$this->assertConditionsMet();
	}
}
