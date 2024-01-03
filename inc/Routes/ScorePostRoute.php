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
	 * WP User.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public array $user;

	/**
	 * Request Callback.
	 *
	 * @since 1.0.0
	 *
	 * @return WP_REST_Response
	 */
	public function request(): \WP_REST_Response {
		$this->user['user']     = $this->request->get_json_params()['user'];
		$this->user['quiz']     = $this->request->get_json_params()['userQuiz'];
		$this->user['question'] = $this->request->get_json_params()['userQuestion'];
		$this->user['answer']   = $this->request->get_json_params()['userAnswer'];
		$this->user['score']    = $this->request->get_json_params()['userScore'];

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
		if ( ! preg_match( '/[1-9]+/', $this->user['question'] ) || ! preg_match( '/^[0-9]$/', $this->user['answer'] ) ) {
			$response = [
				'status'  => 400,
				'message' => 'Bad Request!',
				'request' => [
					'userQuestion' => $this->user['question'],
					'userAnswer'   => $this->user['answer'],
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
				'quiz'          => [
					'ID'    => $this->user['quiz'],
					'title' => get_the_title( $this->user['quiz'] ),
				],
				'question'      => [
					'ID'         => $this->user['question'],
					'title'      => get_the_title( $this->user['question'] ),
					'userAnswer' => $this->user['answer'],
				],
				'answer'        => $this->get_correct_answer(),
				'isUserCorrect' => $this->is_answer_correct(),
				'scoreID'       => $post,
				'percentage'    => $this->get_percentage(),
			],
		];
	}

	/**
	 * Create Score Post.
	 *
	 * @since 1.0.0
	 *
	 * @return integer
	 */
	protected function create_score_post(): int {
		$posts = new \WP_Query(
			[
				'post_type'   => Score::$name,
				'post_status' => 'publish',
				'author'      => $this->user['user']['id'],
				'meta_key'    => 'xama_score_quiz_id',
				'meta_value'  => $this->user['quiz'],
			]
		);

		if ( ! $posts->found_posts ) {
			/**
			 * Filter Score title.
			 *
			 * @since 1.0.0
			 *
			 * @param string $user_login User Login.
			 * @param string $user_quiz User Quiz ID.
			 * @return string
			 */
			$user_login = (string) apply_filters( 'xama_score_title', $this->user['user']['login'], $this->user['quiz'] );

			$this->user['score'] = wp_insert_post(
				[
					'post_type'   => Score::$name,
					'post_status' => 'publish',
					'post_title'  => $user_login . ' | ' . get_the_title( $this->user['quiz'] ),
					'post_author' => $this->user['user']['id'],
				]
			);

			update_post_meta( $this->user['score'], 'xama_score_quiz_id', $this->user['quiz'] );
		}

		return $this->user['score'];
	}

	/**
	 * Create Score Meta.
	 *
	 * @since 1.0.0
	 *
	 * @return integer
	 */
	protected function create_score_meta(): int {
		if ( ! $this->user['score'] ) {
			return 0;
		}

		$total_score     = $this->get_total_score();
		$total_questions = count( xama_get_questions( $this->user['quiz'] ) );

		/**
		 * Set user's total score.
		 * Did the user get it right, TRUE OR FALSE ?
		 * Set user's selected option.
		 * Set user's percentage.
		 */
		update_post_meta( $this->user['score'], 'xama_score_total', $this->is_answer_correct() ? $total_score + 1 : $total_score );
		update_post_meta( $this->user['score'], 'xama_score_status_' . $this->user['question'], (int) $this->is_answer_correct() );
		update_post_meta( $this->user['score'], 'xama_score_answer_' . $this->user['question'], $this->user['answer'] );
		update_post_meta( $this->user['score'], 'xama_score_percentage', ( $this->get_total_score() / $total_questions ) * 100 );

		/**
		 * Fires after Score has been saved.
		 *
		 * @since 1.0.0
		 *
		 * @param $user_quiz     User Quiz ID.
		 * @param $user_question User Question ID.
		 * @param $is_correct    Did user get answer correctly?
		 * @return void;
		 */
		do_action( 'xama_score_save', $this->user['quiz'], $this->user['question'], $this->is_answer_correct() );

		return $this->user['score'];
	}

	/**
	 * Check if answer is correct.
	 *
	 * @since 1.0.0
	 *
	 * @return boolean
	 */
	protected function is_answer_correct(): bool {
		if ( (int) $this->get_correct_answer() === (int) $this->user['answer'] ) {
			return true;
		}

		return false;
	}

	/**
	 * Get Correct Answer.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function get_correct_answer(): string {
		if ( ! isset( $this->user['question'] ) ) {
			return '';
		}

		return get_post_meta( $this->user['question'], 'xama_answer', true );
	}

	/**
	 * Get Percentage.
	 *
	 * @since 1.0.0
	 *
	 * @return integer
	 */
	protected function get_percentage(): int {
		if ( ! isset( $this->user['score'] ) ) {
			return 0;
		}

		return (int) get_post_meta( $this->user['score'], 'xama_score_percentage', true );
	}

	/**
	 * Get Total Score.
	 *
	 * @since 1.0.0
	 *
	 * @return integer
	 */
	protected function get_total_score(): int {
		if ( ! isset( $this->user['score'] ) ) {
			return 0;
		}

		return (int) get_post_meta( $this->user['score'], 'xama_score_total', true );
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
