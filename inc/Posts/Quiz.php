<?php
/**
 * Quiz Post type.
 *
 * This class defines the Quiz custom post type for the Xama
 * plugin. It is a fundamental post type and establishes the base
 * for other custom post types used in the plugin.
 *
 * @package Xama
 */

namespace Xama\Posts;

use Xama\Core\Settings;
use Xama\Abstracts\Post;

/**
 * Quiz class.
 */
class Quiz extends Post {
	/**
	 * Post type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public static $name = 'xama_quiz';

	/**
	 * Return singular label.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_singular_label(): string {
		return 'Quiz';
	}

	/**
	 * Return plural label.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_plural_label(): string {
		return 'Quizzes';
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
		 * Filter Quiz post support.
		 *
		 * @since 1.0.0
		 *
		 * @param array Support options.
		 * @return array
		 */
		return (array) apply_filters( 'xama_quiz_supports', [ 'title' ] );
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
		 * Fires after saving Xama quiz.
		 *
		 * @since 1.0.0
		 *
		 * @param int      $post_id Post ID.
		 * @param \WP_Post $post WP Post.
		 */
		do_action( 'xama_quiz_save', $post_id, $post );
	}

	/**
	 * Register Post columns.
	 *
	 * @since 1.0.0
	 *
	 * @param array $columns Post columns.
	 * @return array
	 */
	public function register_post_columns( $columns ): array {
		unset( $columns['date'] );

		$columns['url']       = esc_html__( 'URL', Settings::DOMAIN );
		$columns['questions'] = esc_html__( 'Number of Questions', Settings::DOMAIN );
		$columns['date']      = esc_html__( 'Date', Settings::DOMAIN );

		/**
		 * Filter Xama quiz columns.
		 *
		 * @since 1.0.0
		 *
		 * @param array $columns Post columns.
		 * @return array
		 */
		return (array) apply_filters( 'xama_quiz_columns', $columns );
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
		switch ( $column ) {
			case 'questions':
				echo $this->get_number_of_questions( $post_id );
				break;

			case 'url':
				echo $this->get_permalink( $post_id );
				break;
		}

		/**
		 * Fires after Quiz columns data registration.
		 *
		 * @since 1.0.0
		 *
		 * @param array $column Column names.
		 * @param int   $post_id Post ID.
		 */
		do_action( 'xama_quiz_column_data', $column, $post_id );
	}

	/**
	 * Post URL slug on rewrite.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function url_slug(): string {
		return 'quiz';
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
		 * Filter Quiz visibility in REST.
		 *
		 * @since 1.0.0
		 *
		 * @param bool Whether to show in REST or not.
		 * @return bool
		 */
		return (bool) apply_filters( 'xama_quiz_visible_in_rest', false );
	}

	/**
	 * Get number of Questions.
	 *
	 * @since 1.0.0
	 *
	 * @param int $post_id Quiz ID.
	 * @return string
	 */
	protected function get_number_of_questions( $post_id ): string {
		return count(
			get_posts(
				[
					'post_type'      => Question::$name,
					'post_status'    => 'publish',
					'posts_per_page' => -1,
					'meta_key'       => 'xama_quiz_id',
					'meta_value'     => $post_id,
				]
			)
		);
	}
}
