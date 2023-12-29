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

	public function test_register_auth_middleware() {
		$post_types = [
			'xama_quiz',
			'xama_question',
			'xama_score',
		];

		$user             = new stdClass();
		$user->ID         = 1;
		$user->user_login = 'johndoe';
		$user->roles[0]   = 'xama';

		\WP_Mock::expectFilter( 'xama_auth_pages', $post_types );

		\WP_Mock::userFunction( 'get_the_ID' )
			->once()
			->andReturn( 1 );

		\WP_Mock::userFunction( 'get_post_type' )
			->once()
			->andReturn( 'xama_quiz' );

		\WP_Mock::userFunction( 'is_user_logged_in' )
			->once()
			->andReturn( true );

		\WP_Mock::userFunction( 'wp_get_current_user' )
			->once()
			->andReturn( $user );

		$this->auth->register_auth_middleware();

		$this->assertConditionsMet();
	}

	public function test_register_auth_login() {
		$this->auth->register_auth_login();

		$this->assertConditionsMet();
	}

	public function test_register_auth_logout() {
		$this->auth->register_auth_logout();

		$this->assertConditionsMet();
	}
}
