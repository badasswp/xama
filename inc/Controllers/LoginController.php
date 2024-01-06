<?php
/**
 * Login Controller.
 *
 * This class is responsible for handling form data sent from the
 * Login template/page. User data/details are validated, sanitized
 * and then used to authenticate Users.
 *
 * @package Xama
 */

namespace Xama\Controllers;

use Xama\Core\Settings;
use Xama\Abstracts\Controller;

class LoginController extends Controller implements \Xama\Interfaces\Controller {
	/**
	 * Controller Rules.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public array $rules = [
		'xama_username' => 'email',
		'xama_password' => 'password',
	];

	/**
	 * Run logic.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function run(): void {
		$_POST['http_error_msgs'] = $this->validate();

		if ( $_POST['http_error_msgs'] ) {
			return;
		}

		$this->auth_user();
	}

	/**
	 * Authenticate User.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function auth_user(): void {
		$user = wp_authenticate( $this->data['xama_username'], $this->data['xama_password'] );

		if ( is_wp_error( $user ) ) {
			$_POST['http_error_msgs'][] = 'Authentication Failed! Please enter your username and password again...';
			return;
		}

		$this->reauth_user();
	}

	/**
	 * Re-authenticate User.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function reauth_user( $exit = true ): void {
		if ( is_user_logged_in() ) {
			wp_logout();
		}

		// Get User...
		$user = get_user_by( 'login', $this->data['xama_username'] );

		if ( ! $user ) {
			return;
		}

		// Set User...
		wp_set_current_user( $user->ID, $this->data['xama_username'] );
		wp_set_auth_cookie( $user->ID );

		// Redirect User...
		$url = isset( $_GET['id'] ) ? get_permalink( $_GET['id'] ) : home_url();
		wp_redirect( $url );

		if ( $exit ) {
			exit;
		}
	}
}
