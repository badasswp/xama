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

	public function test_auth_user() {
		$controller = Mockery::mock( LoginController::class )->makePartial();

		$controller->data = [
			'xama_nonce'    => 'xama_nonce',
			'xama_username' => 'john@doe.com',
			'xama_password' => 'johndoe',
		];

		$user     = new stdClass();
		$user->ID = 1;

		\WP_Mock::userFunction( 'wp_authenticate' )
			->once()
			->with( 'john@doe.com', 'johndoe' )
			->andReturn( $user );

		\WP_Mock::userFunction( 'is_wp_error' )
			->once()
			->with( $user )
			->andReturn( false );

		$controller->shouldAllowMockingProtectedMethods();

		$controller->shouldReceive( 'reauth_user' )->once();

		$controller->auth_user();

		$this->assertConditionsMet();
	}

	public function test_reauth_user() {
		$controller = Mockery::mock( LoginController::class )->makePartial();

		$controller->data = [
			'xama_nonce'    => 'xama_nonce',
			'xama_username' => 'john@doe.com',
			'xama_password' => 'johndoe',
		];

		$user     = new stdClass();
		$user->ID = 1;

		\WP_Mock::userFunction( 'is_user_logged_in' )
			->once()
			->andReturn( true );

		\WP_Mock::userFunction( 'wp_logout' )
			->once()
			->andReturn( null );

		\WP_Mock::userFunction( 'get_user_by' )
			->once()
			->with( 'login', 'john@doe.com' )
			->andReturn( $user );

		\WP_Mock::userFunction( 'wp_set_current_user' )
			->once()
			->with( 1, 'john@doe.com' )
			->andReturn( null );

		\WP_Mock::userFunction( 'wp_set_auth_cookie' )
			->once()
			->with( 1 )
			->andReturn( null );

		\WP_Mock::userFunction( 'home_url' )
			->once()
			->andReturn( 'https://example.com' );

		\WP_Mock::userFunction( 'wp_redirect' )
			->once()
			->with( 'https://example.com' )
			->andReturn( null );

		$controller->reauth_user( false );

		$this->assertConditionsMet();
	}
}
