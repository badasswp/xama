<?php
/**
 * Question Post type.
 *
 * This class defines the Question post type for the Xama
 * plugin. It is used to create and manage questions with options
 * and other features within the plugin.
 *
 * @package Xama
 */

namespace Xama\Posts;

use Xama\Core\Settings;
use Xama\Abstracts\Post;

/**
 * Question class.
 */
class Question extends Post {
	/**
	 * Post type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public static $name = 'xama_question';

	/**
	 * Return singular label.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_singular_label(): string {
		return 'Question';
	}

	/**
	 * Return plural label.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_plural_label(): string {
		return 'Questions';
	}

	/**
	 * Return supports.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_supports(): array {
		/**
		 * Filter Question post support.
		 *
		 * @since 1.0.0
		 *
		 * @param array Support options.
		 * @return array
		 */
		return (array) apply_filters( 'xama_question_supports', [ 'title', 'thumbnail' ] );
	}

	/**
	 * Save Post Type.
	 *
	 * @since 1.0.0
	 *
	 * @param int      $post_id Post ID.
	 * @param \WP_Post $post WP Post.
	 * @return void
	 */
	public function save_post_type( $post_id, $post ): void {
		/**
		 * Fires after saving Xama question.
		 *
		 * @since 1.0.0
		 *
		 * @param int      $post_id Post ID.
		 * @param \WP_Post $post WP Post.
		 */
		do_action( 'xama_question_save', $post_id, $post );
	}

	/**
	 * Register Post columns.
	 *
	 * @since 1.0.0
	 *
	 * @param array $columns Post Columns.
	 * @return array
	 */
	public function register_post_columns( $columns ): array {
		unset( $columns['date'] );

		$columns['quiz']   = esc_html__( 'Quiz', Settings::DOMAIN );
		$columns['answer'] = esc_html__( 'Answer', Settings::DOMAIN );
		$columns['passed'] = esc_html__( 'People Passed (%)', Settings::DOMAIN );
		$columns['date']   = esc_html__( 'Date', Settings::DOMAIN );

		/**
		 * Filter Xama question columns.
		 *
		 * @since 1.0.0
		 *
		 * @param array $columns Post columns.
		 * @return array
		 */
		return (array) apply_filters( 'xama_question_columns', $columns );
	}

	/**
	 * Register Post column data.
	 *
	 * @since 1.0.0
	 *
	 * @param array $column Column names.
	 * @param int   $post_id Post ID.
	 * @return void
	 */
	public function register_post_column_data( $column, $post_id ): void {
		$quiz_id     = get_post_meta( $post_id, 'xama_quiz_id', true );
		$quiz_answer = get_post_meta( $post_id, 'xama_answer', true );
		$quiz_title  = get_the_title( $quiz_id ? $quiz_id : '' );
		$quiz_answer = $quiz_answer ? $quiz_answer : '';

		switch ( $column ) {
			case 'quiz':
				echo esc_html( $quiz_title );
				break;

			case 'answer':
				echo esc_html( Settings::OPTIONS[ $quiz_answer ] );
				break;

			case 'passed':
				echo esc_html( $this->get_percentage( $post_id ) );
				break;
		}

		/**
		 * Fires after Question columns data registration.
		 *
		 * @since 1.0.0
		 *
		 * @param array $column Column names.
		 * @param int   $post_id Post ID.
		 */
		do_action( 'xama_question_column_data', $column, $post_id );
	}

	/**
	 * Post URL slug on rewrite.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function url_slug(): string {
		return 'question';
	}

	/**
	 * Is Post visible in REST.
	 *
	 * @since 1.0.0
	 *
	 * @return boolean
	 */
	public function is_post_visible_in_rest(): bool {
		/**
		 * Filter Question visibility in REST.
		 *
		 * @since 1.0.0
		 *
		 * @param bool Whether to show in REST or not.
		 * @return bool
		 */
		return (bool) apply_filters( 'xama_question_visible_in_rest', false );
	}

	/**
	 * Get percentage of passes.
	 *
	 * @since 1.0.0
	 *
	 * @param int Post ID $post_id
	 * @return integer
	 */
	protected function get_percentage( $post_id ): int {
		$failed = count(
			get_posts(
				[
					'numberposts' => -1,
					'post_type'   => Score::$name,
					'meta_key'    => 'xama_score_status_' . $post_id,
					'meta_value'  => '0',
				]
			)
		);

		$passed = count(
			get_posts(
				[
					'numberposts' => -1,
					'post_type'   => Score::$name,
					'meta_key'    => 'xama_score_status_' . $post_id,
					'meta_value'  => '1',
				]
			)
		);

		$total = $passed + $failed;

		if ( ! ( $total > 0 ) ) {
			return 0;
		}

		$percentage = number_format( ( $passed / $total ) * 100, 0 );

		return $percentage;
	}

	/**
	 * Get Questions belonging to ID.
	 *
	 * @param integer $id Post ID.
	 * @return array
	 */
	public static function get_posts_by_id( $id ): array {
		if ( ! isset( $id ) ) {
			return [];
		}

		$posts = get_posts(
			[
				'post_type'      => static::$name,
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'meta_key'       => 'xama_quiz_id',
				'meta_value'     => $id,
			]
		);

		return $posts;
	}
}
