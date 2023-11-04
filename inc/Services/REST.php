<?php
/**
 * REST Service.
 *
 * This service is responsible for binding REST Routes to
 * WordPress. All routes are defined within the Routes folder,
 * custom routes can also be injected here.
 *
 * @package Xama
 */

namespace Xama\Services;

use Xama\Abstracts\Service;
use Xama\Routes\QuizGetRoute;
use Xama\Interfaces\Registrable;

class REST extends Service implements Registrable {
	/**
	 * Set up.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->routes = [
			QuizGetRoute::class,
		];

		/**
		 * Filter list of WP REST Routes.
		 *
		 * @since 1.0.0
		 *
		 * @param array $routes WP Rest Routes.
		 * @return array
		 */
		$this->routes = (array) apply_filters( 'xama_rest_routes', $this->routes );
	}

	/**
	 * Bind to WP.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'rest_api_init', [ $this, 'register_rest_routes' ] );
	}

	/**
	 * Register Routes.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_rest_routes(): void {
		foreach ( $this->routes as $route ) {
			$route = new $route();
		}
	}
}
