<?php
/**
 * Answer Meta box.
 *
 * This class defines a meta box for managing answers
 * related to quiz questions. It is used in the Question
 * post type edit page.
 *
 * @package Xama
 */

namespace Xama\MetaBoxes;

use Xama\Core\Settings;
use Xama\Posts\Question;
use Xama\Abstracts\MetaBox;

/**
 * Answer class.
 */
class Answer extends MetaBox {
	/**
	 * Meta box name.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public static $name = 'xama_mb_answer';

	/**
	 * Return meta box heading.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_heading(): string {
		return 'Question Answer';
	}

	/**
	 * Return parent post type.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_post_type(): string {
		return Question::$name;
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
		return '';
	}

	/**
	 * Answer Options.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public string $options = '';

	/**
	 * Return callback.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_Post $post WP Post.
	 * @return void
	 */
	public function get_metabox_callback( $post ): void {
		$answer  = get_post_meta( $post->ID, 'xama_answer', true );
		$options = get_post_meta( $post->ID, 'xama_options', true ) ?: array_fill( 0, 4, '' );

		foreach ( array_slice( Settings::OPTIONS, 0, count( $options ) ) as $key => $value ) {
			$this->options .= sprintf(
				'<option value="%1$s" %3$s>
					%2$s
				</option>',
				esc_attr( $key ),
				esc_html__( $value, Settings::DOMAIN ),
				selected( $answer, esc_attr( $key ), false )
			);
		}

		printf(
			'<select
				class="widefat"
				name="xama_answer"
				style="margin-top: 5px;"
			>%1$s</select>',
			$this->options
		);
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
		update_post_meta( $post_id, 'xama_answer', sanitize_text_field( $_POST['xama_answer'] ) );
	}
}
