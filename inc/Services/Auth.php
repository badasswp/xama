<?php
/**
 * Auth Service.
 *
 * This service is responsible for binding Middleware services
 * to specific post types. It is designed to ensure secure access
 * to certain areas of the application.
 *
 * @package Xama
 */

namespace Xama\Services;

use Xama\Posts\Quiz;
use Xama\Posts\Question;
use Xama\Posts\Score;

use Xama\Abstracts\Service;
use Xama\Interfaces\Registrable;
use Xama\Middleware\WPAuthMiddleware;

class Auth extends Service implements Registrable {
	/**
	 * Middleware Instance.
	 *
	 * @since 1.0.0
	 *
	 * @var \WPAuthMiddleware
	 */
	public $auth;

	/**
	 * Set up.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->auth = new WPAuthMiddleware();
	}

	/**
	 * Bind to WP.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'wp_login', [ $this, 'register_auth_login' ] );
		add_action( 'wp_logout', [ $this, 'register_auth_logout' ] );
		add_action( 'template_redirect', [ $this, 'register_auth_middleware' ] );
	}

	/**
	 * Register Middleware implementation.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_auth_middleware() {
		$post_types = [
			Quiz::$name,
			Question::$name,
			Score::$name,
		];

		/**
		 * Filter list of restricted post types.
		 *
		 * @since 1.0.0
		 *
		 * @param array $post_types Xama post types.
		 * @return string
		 */
		$post_types = (array) apply_filters( 'xama_auth_pages', $post_types );

		if ( in_array( get_post_type(), $post_types, true ) ) {
			$this->auth->authenticate();
		}
	}

	/**
	 * Register Login implementation.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_auth_login() {
		$this->auth->login();
	}

	/**
	 * Register Logout implementation.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_auth_logout() {
		$this->auth->logout();
	}
}
