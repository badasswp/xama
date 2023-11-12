<?php

namespace Xama\Tests\Services;

use Mockery;
use stdClass;
use Xama\Core\Settings;
use Xama\Services\Auth;
use WP_Mock\Tools\TestCase;
use Xama\Middleware\WPAuthMiddleware;

/**
 * @covers Auth
 */
class AuthTest extends TestCase {
	private $auth;

	public function setUp(): void {
		\WP_Mock::setUp();

		$this->auth = new Auth();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_register() {
		\WP_Mock::expectActionAdded( 'wp_login', [ $this->auth, 'register_auth_login' ] );
		\WP_Mock::expectActionAdded( 'wp_logout', [ $this->auth, 'register_auth_logout' ] );
		\WP_Mock::expectActionAdded( 'template_redirect', [ $this->auth, 'register_auth_middleware' ] );

		$this->auth->register();

		$this->assertConditionsMet();
	}
}
