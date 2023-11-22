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
	 * Response Callback.
	 *
	 * @since 1.0.0
	 *
	 * @return WP_REST_Response
	 */
	public function response(): \WP_REST_Response {
		if ( ! preg_match( '/[1-9]+/', $this->user_question ) || ! preg_match( '/^[1-4]$/', $this->user_answer ) ) {
			$response = [
				'status'  => 400,
				'message' => 'Bad Request!',
				'request' => [
					'userQuestion' => $this->user_question,
					'userAnswer'   => $this->user_answer,
				],
			];

			return rest_ensure_response( $response );
		}

		return rest_ensure_response( $this->get_response() );
	}

	/**
	 * Get Response for valid WP REST request.
	 *
	 * This method creates the Score post if it doesn't exist
	 * and stores the appropriate meta data for each Score post.
	 * Each Score post contains multiple meta data.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function get_response(): array {
		$post = $this->create_score_post();
		$meta = $this->create_score_meta();

		return [
			'status'  => 201,
			'message' => 'Score Post created successfully!',
			'data'    => [
				'question'      => [
					'ID'         => $this->user_question,
					'title'      => get_the_title( $this->user_question ),
					'userAnswer' => Settings::OPTIONS[ $this->user_answer ],
				],
				'answer'        => Settings::OPTIONS[ $this->get_correct_answer() ],
				'isUserCorrect' => $this->is_answer_correct(),
				'scoreID'       => $post,
			],
		];
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
