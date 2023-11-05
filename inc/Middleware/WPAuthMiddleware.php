<?php
/**
 * WP Auth Middleware.
 *
 * This class represents the WordPress Authentication Middleware.
 * It extends the Middleware abstract class, providing the specific
 * implementation required for user authentication.
 *
 * @package Xama
 */

namespace Xama\Middleware;

use Xama\Abstracts\Middleware;

/**
 * WPAuthMiddleware class.
 */
class WPAuthMiddleware extends Middleware implements \Xama\Interfaces\Middleware {
	/**
	 * Redirect Page slug.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public string $redirect_page;

	/**
	 * Set up.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->redirect_page = 'login';
	}

	/**
	 * Authenticate user.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function authenticate(): void {
		if ( ! is_user_logged_in() ) {
			$this->redirect();
		}

		if ( ! in_array( wp_get_current_user()->roles[0], [ 'administrator', 'xama' ], true ) ) {
			$this->redirect();
		}
	}

	/**
	 * On Login.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function login(): void {
		// Do nothing for now... possibly set expiry time.
	}

	/**
	 * On Logout.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function logout(): void {
		// Do nothing for now... possibly unset expiry time.
	}
}
