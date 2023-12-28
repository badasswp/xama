<?php
/**
 * Controller Service.
 *
 * This service is responsible for binding Controller logic
 * from GET & POST form requests to specific WP pages.
 *
 * @package Xama
 */

namespace Xama\Services;

use Xama\Abstracts\Service;
use Xama\Interfaces\Registrable;
use Xama\Controllers\SignUpController;

class Controller extends Service implements Registrable {
	/**
	 * Controller pages.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public array $pages;

	/**
	 * Set up.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->pages = [
			'sign-up' => SignUpController::class,
		];
	}

	/**
	 * Bind to WP.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'wp', [ $this, 'register_controller' ] );
	}

	/**
	 * Register Controller implementation.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_controller() {
		$page = get_queried_object()->post_name;

		/**
		 * Filter list of controlled pages.
		 *
		 * @since 1.0.0
		 *
		 * @param array $pages Controller pages.
		 * @return array
		 */
		$this->pages = (array) apply_filters( 'xama_controllers', $this->pages );

		if ( is_page() && in_array( $page, array_keys( $this->pages ), true ) ) {
			$controller = new $this->pages[ $page ]();
		}
	}
}
