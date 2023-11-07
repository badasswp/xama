<?php

namespace Xama\Tests\Abstracts;

use stdClass;
use WP_Mock\Tools\TestCase;
use Xama\Abstracts\Middleware;

/**
 * @covers Middleware
 */
class MiddlewareTest extends TestCase {
	private $middleware;

	public function setUp(): void {
		\WP_Mock::setUp();

		$this->middleware                = new ConcreteMiddleware();
		$this->middleware->redirect_page = 'login';
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_redirect() {
		$page     = new stdClass();
		$page->ID = 1;

		\WP_Mock::userFunction( 'get_page_by_path' )
			->once()
			->andReturn( $page );

		\WP_Mock::userFunction( 'get_permalink' )
			->once()
			->andReturn( 'http://example.com/login' );

		\WP_Mock::userFunction( 'wp_redirect' )
			->once();

		$this->middleware->redirect( false );

		$this->assertConditionsMet();
	}
}

class ConcreteMiddleware extends Middleware {
	public function login(): void {}
	public function logout(): void {}
	public function authenticate(): void {}
}
