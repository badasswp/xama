<?php
/**
 * Route Interface.
 *
 * This interface defines a contract for routes
 * and defines common methods that derived classes
 * should implement.
 *
 * @package Xama
 */

namespace Xama\Interfaces;

interface Route {
	/**
	 * Request Callback.
	 *
	 * @return WP_REST_Response
	 */
	public function request(): \WP_REST_Response;

	/**
	 * Response Callback.
	 *
	 * @return WP_REST_Response
	 */
	public function response(): \WP_REST_Response;

	/**
	 * Permissions callback for endpoints.
	 *
	 * @return bool
	 */
	public function is_user_permissible(): bool;
}
