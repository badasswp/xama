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

	public function test_authenticate_for_logged_in_user() {
		$user           = new stdClass();
		$user->roles[0] = 'editor';

		$page     = new stdClass();
		$page->ID = 1;

		$this->middleware->exit = false;

		\WP_Mock::userFunction( 'is_user_logged_in' )
			->once()
			->andReturn( true );

		\WP_Mock::userFunction( 'wp_get_current_user' )
			->once()
			->andReturn( $user );

		\WP_Mock::userFunction( 'get_page_by_path' )
			->once()
			->andReturn( $page );

		\WP_Mock::userFunction( 'get_permalink' )
			->once()
			->andReturn( 'http://example.com/login' );

		\WP_Mock::userFunction( 'wp_redirect' )
			->once();

		$this->middleware->authenticate();

		$this->assertConditionsMet();
	}
}
