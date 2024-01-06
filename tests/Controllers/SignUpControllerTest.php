<?php

namespace Xama\Tests\Controllers;

use Mockery;
use stdClass;
use Xama\Core\Settings;
use WP_Mock\Tools\TestCase;
use Xama\Controllers\SignUpController;

/**
 * @covers SignUpController
 */
class SignUpControllerTest extends TestCase {
	private $controller;

	public function setUp(): void {
		\WP_Mock::setUp();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}
}
