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

	public function test_run() {
		$controller = Mockery::mock( SignUpController::class )->makePartial();

		$controller->shouldAllowMockingProtectedMethods();
		$controller->shouldReceive( 'validate' )->andReturn( [] );
		$controller->shouldReceive( 'create_user' )->once();

		$controller->run();

		$this->assertTrue( empty( $_POST['http_error_msgs'] ) );
		$this->assertConditionsMet();
	}

	public function test_create_user() {
		$controller = Mockery::mock( SignUpController::class )->makePartial();

		$controller->data = [
			'xama_nonce'    => 'xama_nonce',
			'xama_username' => 'john@doe.com',
			'xama_password' => 'johndoe',
			'xama_fullname' => 'John Doe',
		];

		$user_id = 1;

		\WP_Mock::userFunction( 'sanitize_text_field' )
			->times( 4 )
			->with( 'John Doe' )
			->andReturn( 'John Doe' );

		\WP_Mock::userFunction( 'wp_insert_user' )
			->once()
			->with(
				[
					'user_login'           => 'john@doe.com',
					'user_pass'            => 'johndoe',
					'user_email'           => 'john@doe.com',
					'display_name'         => 'John Doe',
					'user_nicename'        => 'John Doe',
					'nickname'             => 'John Doe',
					'last_name'            => 'John Doe',
					'show_admin_bar_front' => false,
					'role'                 => 'xama',
				]
			)
			->andReturn( $user_id );

		\WP_Mock::userFunction( 'is_wp_error' )
			->once()
			->with( 1 )
			->andReturn( false );

		$controller->shouldAllowMockingProtectedMethods();

		$controller->shouldReceive( 'reauth_user' )->once();

		$controller->create_user();

		$this->assertTrue( empty( $_POST['http_error_msgs'] ) );
		$this->assertConditionsMet();
	}

	public function test_reauth_user() {
		$controller = Mockery::mock( SignUpController::class )->makePartial();

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
