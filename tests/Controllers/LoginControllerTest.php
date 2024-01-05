<?php

namespace Xama\Tests\Controllers;

use stdClass;
use Xama\Core\Settings;
use Xama\Controllers\LoginController;
use WP_Mock\Tools\TestCase;

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
}
