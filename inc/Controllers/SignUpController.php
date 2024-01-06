<?php
/**
 * SignUp Controller.
 *
 * This class is responsible for handling form data sent from the
 * Sign Up template/page. User data/details are validated, sanitized
 * and then stored in the WP Users table.
 *
 * @package Xama
 */

namespace Xama\Controllers;

use Xama\Core\Settings;
use Xama\Abstracts\Controller;

class SignUpController extends Controller implements \Xama\Interfaces\Controller {
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
		'xama_fullname' => 'name',
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

		$this->create_user();
	}

	/**
	 * Create User.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function create_user(): void {
		$user_id = wp_insert_user(
			[
				'user_login'           => $this->data['xama_username'],
				'user_pass'            => $this->data['xama_password'],
				'user_email'           => $this->data['xama_username'],
				'display_name'         => sanitize_text_field( $this->data['xama_fullname'] ),
				'user_nicename'        => sanitize_text_field( $this->data['xama_fullname'] ),
				'nickname'             => sanitize_text_field( $this->data['xama_fullname'] ),
				'last_name'            => sanitize_text_field( $this->data['xama_fullname'] ),
				'show_admin_bar_front' => false,
				'role'                 => Settings::SLUG,
			]
		);

		if ( is_wp_error( $user_id ) ) {
			$_POST['http_error_msgs'][] = $user_id->get_error_message();
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
