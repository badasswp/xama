<?php
/**
 * Score POST Route.
 *
 * This route represents the Score POST route, which is responsible
 * for storing user scores via a WP RESTful API endpoint. It extends
 * the Route abstract class, providing specific implementation for
 * handling POST requests to score quiz scores.
 *
 * @package Xama
 */

namespace Xama\Routes;

use Xama\Posts\Score;
use Xama\Core\Settings;
use Xama\Abstracts\Route;

/**
 * Score POST Route class.
 */
class ScorePostRoute extends Route implements \Xama\Interfaces\Route {
	/**
	 * Get, Post, Put, Patch, Delete.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public string $method = 'POST';

	/**
	 * WP REST Endpoint e.g. /wp-json/xama/v1/score.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public string $endpoint = '/score';

	/**
	 * Request Callback.
	 *
	 * @since 1.0.0
	 *
	 * @return WP_REST_Response
	 */
	public function request(): \WP_REST_Response {
		$this->user_question = $this->request->get_json_params()['id'];
		$this->user_answer   = $this->request->get_json_params()['answer'];

		return $this->response();
	}

	/**
	 * Permissions callback for endpoints.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_user_permissible(): bool {
		return in_array( wp_get_current_user()->roles[0], [ 'administrator', 'xama' ], true );
	}
}
