<?php
/**
 * Quiz GET Route.
 *
 * This route represents the Quiz GET route, which is responsible
 * for retrieving quiz data via a WP RESTful API endpoint. It extends
 * the Route abstract class, providing specific implementation for
 * handling GET requests to fetch quiz posts.
 *
 * @package Xama
 */

namespace Xama\Routes;

use Xama\Abstracts\Route;

/**
 * Quiz GET Route class.
 */
class QuizGetRoute extends Route implements \Xama\Interfaces\Route {
	/**
	 * Get, Post, Put, Patch, Delete.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public string $method = 'GET';

	/**
	 * WP REST Endpoint e.g. /wp-json/xama/v1/quiz.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public string $endpoint = '/quiz';

	/**
	 * Request Callback.
	 *
	 * @since 1.0.0
	 *
	 * @return WP_REST_Response
	 */
	public function request(): \WP_REST_Response {
		$this->data = $this->get_posts();

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
			'message' => 'Quiz Posts successfully fetched!',
			'data'    => $this->data,
		];

		return rest_ensure_response( $response );
	}

	/**
	 * Get Posts.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function get_posts(): array {
		$posts    = xama_get_quizzies();
		$quizzies = [];

		if ( ! isset( $posts ) ) {
			return $quizzies;
		}

		foreach ( $posts as $post ) {
			$quizzies[] = [
				'ID'             => $post->ID,
				'title'          => get_the_title( $post->ID ),
				'content'        => $post->post_content,
				'featured_image' => get_the_post_thumbnail_url( $post->ID, 'full' ),
			];
		}

		return $quizzies;
	}

	/**
	 * Permissions callback for endpoints.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_user_permissible(): bool {
		return current_user_can( 'manage_options' );
	}
}
