<?php
/**
 * Route abstraction.
 *
 * This abstract class defines a foundation for creating
 * route classes which act as WP REST end points for frontend
 * applications.
 *
 * @package Xama
 */

namespace Xama\Abstracts;

/**
 * Route class.
 */
abstract class Route implements \Xama\Interfaces\Route {
	/**
	 * Register REST Route.
	 *
	 * @return void
	 */
	public function register_route(): void {
		register_rest_route(
			'xama/v1',
			$this->endpoint,
			[
				'methods'             => $this->method,
				'callback'            => [ $this, 'callback' ],
				'permission_callback' => $this->is_user_permissible() ? '__return_true' : '__return_false',
			]
		);
	}

	/**
	 * REST Callback.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response
	 */
	public function callback( $request ): \WP_REST_Response {
		$this->request = $request;

		return $this->request();
	}

	/**
	 * Request Callback.
	 *
	 * @since 1.0.0
	 *
	 * @return WP_REST_Response
	 */
	abstract public function request(): \WP_REST_Response;

	/**
	 * Response Callback.
	 *
	 * @since 1.0.0
	 *
	 * @return WP_REST_Response
	 */
	abstract public function response(): \WP_REST_Response;

	/**
	 * Permissions callback for endpoints.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	abstract public function is_user_permissible(): bool;
}
