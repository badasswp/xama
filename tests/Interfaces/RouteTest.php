<?php

namespace Xama\Tests\Interfaces;

use Xama\Interfaces\Route;
use WP_Mock\Tools\TestCase;

/**
 * @covers Route
 */
class RouteTest extends TestCase {
	private $route;

	public function setUp(): void {
		$this->route = $this->getMockForAbstractClass( Route::class );
	}

	public function test_request() {
		$this->route->expects( $this->once() )
			->method( 'request' );

		$this->route->request();

		$this->assertConditionsMet();
	}

	public function test_response() {
		$this->route->expects( $this->once() )
			->method( 'response' );

		$this->route->response();

		$this->assertConditionsMet();
	}

	public function test_is_user_permissible() {
		$this->route->expects( $this->once() )
			->method( 'is_user_permissible' );

		$this->route->is_user_permissible();

		$this->assertConditionsMet();
	}
}
