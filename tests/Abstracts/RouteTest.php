<?php

namespace Xama\Tests\Abstracts;

use stdClass;
use Xama\Abstracts\Route;
use WP_Mock\Tools\TestCase;

/**
 * @covers Route
 */
class RouteTest extends TestCase {
	private $route;

	public function setUp(): void {
		\WP_Mock::setUp();

		$this->route = new ConcreteRoute();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_register_rest_route() {
		\WP_Mock::userFunction( 'register_rest_route' )
			->once()
			->with(
				'xama/v1',
				'/quiz',
				[
					'methods'             => 'GET',
					'callback'            => [ $this->route, 'callback' ],
					'permission_callback' => $this->route->is_user_permissible() ? '__return_true' : '__return_false',
				]
			);

		$this->route->register_route();

		$this->assertConditionsMet();
	}
}

class ConcreteRoute extends Route {
	public $method = 'GET';

	public $endpoint = '/quiz';

	public function is_user_permissible(): bool {
		return true;
	}

	public function request(): \WP_REST_Response {
		return new \WP_REST_Response();
	}

	public function response(): \WP_REST_Response {
		return new \WP_REST_Response();
	}
}
