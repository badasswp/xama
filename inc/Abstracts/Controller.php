<?php
/**
 * Controller abstraction.
 *
 * This abstract class serves as the foundation for creating
 * Controllers responsible for logic handling between GET &
 * POST requests via forms.
 *
 * @package Xama
 */

namespace Xama\Abstracts;

abstract class Controller implements \Xama\Interfaces\Controller {
	/**
	 * POST data.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected array $data = [];

	/**
	 * Set up.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		if ( 'POST' !== $_SERVER['REQUEST_METHOD'] ) {
			return;
		}

		if ( ! isset( $_POST['xama_nonce'] ) || ! wp_verify_nonce( $_POST['xama_nonce'], 'xama_action' ) ) {
			return;
		}

		$this->data = $_POST;
		$this->run();
	}

	/**
	 * Validate $_GET | $_POST data.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function validate(): array {
		$http_error_msgs = [];

		if ( ! isset( $this->rules ) ) {
			return $http_error_msgs;
		}

		foreach ( $this->rules as $field => $rule ) {
			switch ( $rule ) {
				case 'email':
					if ( empty( $this->data[ $field ] ) || ! is_email( $this->data[ $field ] ) ) {
						$http_error_msgs[] = 'Error: Validating Email Address, email must contain a valid email address...';
					}
					break;

				case 'password':
					if ( empty( $this->data[ $field ] ) || strlen( $this->data[ $field ] ) < 6 ) {
						$http_error_msgs[] = 'Error: Validating Password, password must contain a minimum of 6 unique characters...';
					}
					break;

				case 'name':
					if ( empty( $this->data[ $field ] ) || preg_match( '/[0-9]+/', $this->data[ $field ], $matches ) || strlen( $this->data[ $field ] ) < 2 ) {
						$http_error_msgs[] = 'Error: Validating Name, name must contain a valid name...';
					}
					break;
			}
		}

		return $http_error_msgs;
	}

	/**
	 * Run logic.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	abstract public function run(): void;
}
