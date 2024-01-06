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

	public function test_run_aborts_due_to_error_msgs() {
		// Server Req. Method
		$_SERVER['REQUEST_METHOD'] = 'POST';

		// Post Nonce
		$_POST = [
			'xama_nonce'    => 'xama_nonce',
			'xama_username' => 'john',
			'xama_password' => 'jb',
		];

		\WP_Mock::userFunction( 'wp_verify_nonce' )
			->once()
			->with( 'xama_nonce', 'xama_action' )
			->andReturn( true );

		\WP_Mock::userFunction( 'is_email' )
			->once()
			->with( 'john' )
			->andReturn( false );

		$this->controller = new LoginController();

		$this->controller->rules = [
			'xama_username' => 'email',
			'xama_password' => 'password',
		];

		$this->assertConditionsMet();
	}

	public function test_auth_user() {
		$user     = new stdClass();
		$user->ID = null;

		// Server Req. Method
		$_SERVER['REQUEST_METHOD'] = 'POST';

		// Post Nonce
		$_POST = [
			'xama_nonce'    => 'xama_nonce',
			'xama_username' => 'john@doe.com',
			'xama_password' => 'johndoe',
		];

		\WP_Mock::userFunction( 'wp_verify_nonce' )
			->once()
			->with( 'xama_nonce', 'xama_action' )
			->andReturn( true );

		\WP_Mock::userFunction( 'is_email' )
			->once()
			->with( 'john@doe.com' )
			->andReturn( true );

		\WP_Mock::userFunction( 'wp_authenticate' )
			->once()
			->with( 'john@doe.com', 'johndoe' )
			->andReturn( $user );

		\WP_Mock::userFunction( 'is_wp_error' )
			->once()
			->with( $user )
			->andReturn( true );

		// No need to run auth_user(),
		// that is already run by default
		// from the Contructor.
		$this->controller = new LoginController();

		$this->assertTrue( isset( $_POST['http_error_msgs'] ) );
		$this->assertConditionsMet();
	}
}
