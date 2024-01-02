<?php
/**
 * Scores Meta box.
 *
 * This class defines a meta box for displaying score
 * details associated with a quiz. It is used in the Score
 * post type edit page.
 *
 * @package Xama
 */

namespace Xama\MetaBoxes;

use Xama\Posts\Score;
use Xama\Core\Settings;
use Xama\Abstracts\MetaBox;

/**
 * Scores class.
 */
class Scores extends MetaBox {
	/**
	 * Meta box name.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public static $name = 'xama_mb_scores';

	/**
	 * Return meta box heading.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_heading(): string {
		return 'Quiz Scores';
	}

	/**
	 * Return parent post type.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_post_type(): string {
		return Score::$name;
	}

	/**
	 * Return meta box position.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_position(): string {
		return '';
	}

	/**
	 * Return meta box priority.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_priority(): string {
		return 'high';
	}

	/**
	 * Return callback.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_Post $post WP Post.
	 * @return void
	 */
	public function get_metabox_callback( $post ): void {
		$this->scores_meta = get_post_meta( $post->ID );
		$this->scores_auth = $post->post_author;

		printf(
			'<section>
				%1$s
				%2$s
			</section>',
			$this->get_scores_heading_labels_and_data(),
			$this->get_scores_questions_labels_and_data()
		);
	}

	/**
	 * Get Scores Headings.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function get_scores_heading_labels_and_data(): string {
		$user = get_user_by( 'id', $this->scores_auth );

		$scores_metadata_labels = [
			'User Name'       => $user->user_login,
			'User Email'      => $user->user_email,
			'User Score'      => $this->scores_meta['xama_score_total'][0] ?: 0,
			'Total Questions' => xama_get_count_questions( $this->scores_meta['xama_score_quiz_id'][0] ),
		];

		$labels_and_data = '';

		foreach ( $scores_metadata_labels as $label => $value ) {
			$labels_and_data .= sprintf(
				'<p>
					<strong>%1$s</strong><br/>
					%2$s
				</p>',
				esc_html__( $label, Settings::DOMAIN ),
				esc_html( $value )
			);
		}

		return $labels_and_data;
	}

	/**
	 * Get Scores Questions Labels & Data.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function get_scores_questions_labels_and_data(): string {
		$labels_and_data = '';

		foreach ( $this->scores_meta as $key => $value ) {
			if ( strpos( $key, 'xama_score_status_' ) !== false ) {
				$this->id = explode( '_', $key )[3];

				$labels_and_data .= sprintf(
					'%1$s
					<ol style="margin-top: 0; margin-bottom: 20px; padding: 0;">%2$s</ol>',
					$this->get_question_and_score_info( $key, $value ),
					$this->get_question_options()
				);
			}
		}

		return $labels_and_data;
	}

	/**
	 * Get Question & Score info.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key Metadata key.
	 * @param string $value Metadata value.
	 * @return string
	 */
	protected function get_question_and_score_info( $key, $value ): string {
		$post_answer = get_post_meta( $this->id, 'xama_answer', true );
		$user_answer = $this->scores_meta[ 'xama_score_answer_' . $this->id ][0];

		if ( ! isset( Settings::OPTIONS[ $post_answer ] ) || ! isset( Settings::OPTIONS[ $user_answer ] ) ) {
			return '';
		};

		$question_and_score_info = sprintf(
			'<p style="color: %2$s">
				<strong>%1$s</strong><br/>
				<span class="%3$s"></span>
				%6$s: %4$s | %7$s: %5$s
			</p>',
			esc_html( get_the_title( $this->id ) ),
			$value[0] ? esc_attr( 'rebeccapurple' ) : esc_attr( 'red' ),
			$value[0] ? esc_attr( 'dashicons dashicons-yes' ) : esc_attr( 'dashicons dashicons-no' ),
			esc_html__( Settings::OPTIONS[ $post_answer ], Settings::DOMAIN ),
			esc_html__( Settings::OPTIONS[ $user_answer ], Settings::DOMAIN ),
			esc_html__( 'Question\'s Answer', Settings::DOMAIN ),
			esc_html__( 'User\'s Answer', Settings::DOMAIN )
		);

		return $question_and_score_info;
	}

	/**
	 * Get Question options.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function get_question_options(): string {
		$options = get_post_meta( $this->id, 'xama_options', true ) ?: [];
		$html    = '';

		foreach ( $options as $key => $value ) {
			$html .= sprintf(
				'<li>
					%1$s
				</li>',
				esc_html( $value )
			);
		}

		return $html;
	}

	/**
	 * Save Meta box.
	 *
	 * @since 1.0.0
	 *
	 * @param int      $post_id Post ID.
	 * @param \WP_Post $post WP Post.
	 * @return void
	 */
	public function save_meta_box( $post_id, $post ): void {
		// Nothing to do for now...
	}
}
