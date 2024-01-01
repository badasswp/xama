<?php
/**
 * Quiz GET ID Route.
 *
 * This route represents the Quiz GET route, which is responsible
 * for retrieving quiz data via a WP RESTful API endpoint. It extends
 * the Route abstract class, providing specific implementation for
 * handling GET requests to fetch quiz posts.
 *
 * @package Xama
 */

namespace Xama\Routes;

use Xama\Posts\Question;
use Xama\Abstracts\Route;

/**
 * Quiz GET Route class.
 */
class QuizGetIDRoute extends Route implements \Xama\Interfaces\Route {
	/**
	 * Get, Post, Put, Patch, Delete.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public string $method = 'GET';

	/**
	 * WP REST Endpoint e.g. /wp-json/xama/v1/quiz/1.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public string $endpoint = '/quiz/(?P<id>\d+)';

	/**
	 * Response Data.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public array $data;

	/**
	 * Request Callback.
	 *
	 * @since 1.0.0
	 *
	 * @return WP_REST_Response
	 */
	public function request(): \WP_REST_Response {
		$this->data = $this->get_post();

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
		$response = [
			'status'  => 200,
			'message' => 'Quiz Post successfully fetched!',
			'data'    => $this->data,
		];

		return rest_ensure_response( $response );
	}

	/**
	 * Get Post.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function get_post(): array {
		$post_id = $this->request->get_param( 'id' );

		foreach ( xama_get_questions( $post_id ) as $post ) {
			$questions[] = [
				'ID'             => $post->ID,
				'title'          => get_the_title( $post->ID ),
				'content'        => $post->post_content,
				'featured_image' => get_the_post_thumbnail_url( $post->ID, 'full' ),
				'options'        => get_post_meta( $post->ID, 'xama_options', true ),
			];
		}

		$post = get_post( $post_id );

		$quiz = [
			'ID'             => $post->ID,
			'title'          => get_the_title( $post->ID ),
			'content'        => $post->post_content,
			'featured_image' => get_the_post_thumbnail_url( $post->ID, 'full' ),
			'questions'      => $questions,
		];

		return $quiz;
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
