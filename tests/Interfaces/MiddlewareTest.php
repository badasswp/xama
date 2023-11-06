<?php

namespace Xama\Tests\Interfaces;

use Xama\Interfaces\Middleware;
use WP_Mock\Tools\TestCase;

/**
 * @covers Middleware
 */
class MiddlewareTest extends TestCase {
	private $middleware;

	public function setUp(): void {
		$this->middleware = $this->getMockForAbstractClass( Middleware::class );
	}

	public function test_authenticate() {
		$this->middleware->expects( $this->once() )
			->method( 'authenticate' );

		$this->middleware->authenticate();

		$this->assertConditionsMet();
	}

	public function test_login() {
		$this->middleware->expects( $this->once() )
			->method( 'login' );

		$this->middleware->login();

		$this->assertConditionsMet();
	}

	public function test_logout() {
		$this->middleware->expects( $this->once() )
			->method( 'logout' );

		$this->middleware->logout();

		$this->assertConditionsMet();
	}

	public function test_redirect() {
		$this->middleware->expects( $this->once() )
			->method( 'redirect' );

		$this->middleware->redirect();

		$this->assertConditionsMet();
	}
}
