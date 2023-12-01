<?php
/**
 * Score Post type.
 *
 * This class defines the Score post type for the Xama
 * plugin. It is used to store and manage the scores and results
 * of quizzes and assessments in the plugin.
 *
 * @package Xama
 */

namespace Xama\Posts;

use Xama\Core\Settings;
use Xama\Abstracts\Post;

/**
 * Score class.
 */
class Score extends Post {
	/**
	 * Post type.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public static $name = 'xama_score';

	/**
	 * Return singular label.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_singular_label(): string {
		return 'Score';
	}

	/**
	 * Return plural label.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_plural_label(): string {
		return 'Scores';
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
		 * Filter Score post support.
		 *
		 * @since 1.0.0
		 *
		 * @param array Support option.
		 * @return array
		 */
		return (array) apply_filters( 'xama_score_supports', [ 'title' ] );
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
		 * Fires after saving Xama score.
		 *
		 * @since 1.0.0
		 *
		 * @param int      $post_id Post ID.
		 * @param \WP_Post $post WP Post.
		 */
		do_action( 'xama_score_save', $post_id, $post );
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

		$columns['quiz']       = esc_html__( 'Quiz', Settings::DOMAIN );
		$columns['score']      = esc_html__( 'Score', Settings::DOMAIN );
		$columns['total']      = esc_html__( 'Total', Settings::DOMAIN );
		$columns['percentage'] = esc_html__( 'Percentage (%)', Settings::DOMAIN );
		$columns['date']       = esc_html__( 'Date', Settings::DOMAIN );

		/**
		 * Filter Xama score columns.
		 *
		 * @since 1.0.0
		 *
		 * @param array $columns Score columns.
		 * @return array
		 */
		return (array) apply_filters( 'xama_score_columns', $columns );
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
		$id    = get_post_meta( $post_id, 'xama_score_quiz_id', true );
		$quiz  = get_the_title( $id ) ?: '';
		$score = get_post_meta( $post_id, 'xama_score_total', true ) ?: 0;
		$total = xama_get_count_questions( $id );

		$percentage = $total > 0 ? number_format( ( $score / $total ) * 100, 0 ) : 0;

		switch ( $column ) {
			case 'quiz':
				echo esc_html( $quiz );
				break;

			case 'score':
				echo esc_html( $score );
				break;

			case 'total':
				echo esc_html( $total );
				break;

			case 'percentage':
				echo esc_html( $percentage );
				break;
		}

		/**
		 * Fires after Score columns data registration.
		 *
		 * @since 1.0.0
		 *
		 * @param array $column Column names.
		 * @param int   $post_id Post ID.
		 */
		do_action( 'xama_score_column_data', $column, $post_id );
	}

	/**
	 * Post URL slug on rewrite.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function url_slug(): string {
		return 'score';
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
		 * Filter Score visibility in REST.
		 *
		 * @since 1.0.0
		 *
		 * @param bool Whether to show in REST or not.
		 * @return bool
		 */
		return (bool) apply_filters( 'xama_score_visible_in_rest', false );
	}
}
