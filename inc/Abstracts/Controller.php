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
	 */
	public function __construct() {
		if ( 'POST' !== $_SERVER['REQUEST_METHOD'] ) {
			return;
		}

		if ( ! isset( $_POST['xama_nonce'] ) || ! wp_verify_nonce( $_POST['xama_nonce'], 'xama_action' ) ) {
			return;
		}

		$this->data = $_POST;

		// Run Controller...
		$this->run();
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
