<?php

namespace Xama\Tests\Middleware;

use Mockery;
use stdClass;
use Xama\Core\Settings;
use WP_Mock\Tools\TestCase;
use Xama\Middleware\WPAuthMiddleware;

/**
 * @covers WPAuthMiddleware
 */
class WPAuthMiddlewareTest extends TestCase {
	private $middleware;

	public function setUp(): void {
		\WP_Mock::setUp();

		$this->middleware = new WPAuthMiddleware();

		$this->middleware->redirect_page = 'login';
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}
}
