<?php
/**
 * Options Meta box.
 *
 * This class defines a meta box for managing options
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
 * Options class.
 */
class Options extends MetaBox {
	/**
	 * Meta box name.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public static $name = 'xama_mb_options';

	/**
	 * Return meta box heading.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_heading(): string {
		return 'Question Options';
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
		$this->post = $post;

		printf(
			'<section>
				%1$s
				%2$s
				%3$s
			</section>',
			$this->get_buttons(),
			$this->get_options(),
			$this->get_hidden()
		);
	}

	/**
	 * Get Back to Quiz Button.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function get_buttons(): string {
		return sprintf(
			'<div>
				<a
					href="%1$s"
					class="button button-secondary button-large"
					style="margin-top: 5px;"
				>%2$s</a>
				<a
					href="%3$s"
					class="button button-primary button-large"
					style="margin-top: 5px;"
				>%4$s</a>
			</div>',
			esc_url( $this->get_button1_url() ),
			esc_html__( 'Go Back To Quiz', Settings::DOMAIN ),
			esc_url( $this->get_button2_url() ),
			esc_html__( 'Add New Question', Settings::DOMAIN )
		);
	}

	/**
	 * Get Back to Quiz Button URL.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function get_button1_url(): string {
		$id = ! isset( $_GET['quiz_id'] )
			? get_post_meta( $this->post->ID, 'xama_quiz_id', true )
			: $_GET['quiz_id'];

		return sprintf(
			'%1$s/wp-admin/post.php?post=%2$s&action=edit',
			home_url(),
			absint( $id )
		);
	}

	/**
	 * Get Add New Question Button URL.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function get_button2_url(): string {
		$id = ! isset( $_GET['quiz_id'] )
			? get_post_meta( $this->post->ID, 'xama_quiz_id', true )
			: $_GET['quiz_id'];

		return sprintf(
			'%1$s/wp-admin/post-new.php?post_type=%3$s&quiz_id=%2$s',
			home_url(),
			absint( $id ),
			$this->get_post_type()
		);
	}

	/**
	 * Get Options.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function get_options(): string {
		$this->options = '';

		$options = get_post_meta( $this->post->ID, 'xama_options', true ) ?: array_fill( 0, 4, '' );

		foreach ( $options as $key => $value ) {
			$this->options .= sprintf(
				'<p>
					<label for="option%1$s">
						%3$s
					</label>
					<br/>
					<input
						type="text"
						class="widefat"
						name="xama_option_%1$s"
						value="%2$s"
					/>
				</p>',
				esc_attr( $key ),
				esc_attr( $value ),
				esc_html__( 'Option ' . $key, Settings::DOMAIN )
			);
		}

		return $this->options;
	}

	/**
	 * Get hidden input.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function get_hidden(): string {
		return sprintf(
			'<input
				type="hidden"
				name="xama_quiz_id"
				value="%1$s"
			/>',
			isset( $_GET['quiz_id'] )
				? esc_attr( $_GET['quiz_id'] )
				: esc_attr( get_post_meta( $this->post->ID, 'xama_quiz_id', true ) )
		);
	}

	/**
	 * Save Meta box.
	 *
	 * @since 1.0.0
	 *
	 * @param int      $post_id Post ID.
	 * @param \WP_Post $post WP_Post.
	 * @return void
	 */
	public function save_meta_box( $post_id, $post ): void {
		update_post_meta( $post_id, 'xama_option_1', sanitize_text_field( $_POST['xama_option_1'] ) );
		update_post_meta( $post_id, 'xama_option_2', sanitize_text_field( $_POST['xama_option_2'] ) );
		update_post_meta( $post_id, 'xama_option_3', sanitize_text_field( $_POST['xama_option_3'] ) );
		update_post_meta( $post_id, 'xama_option_4', sanitize_text_field( $_POST['xama_option_4'] ) );

		update_post_meta( $post_id, 'xama_quiz_id', sanitize_text_field( $_POST['xama_quiz_id'] ) );
	}
}
