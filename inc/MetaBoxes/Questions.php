<?php
/**
 * Questions Meta box.
 *
 * This class defines a meta box for managing questions
 * associated with a quiz. It is used in the Quiz post
 * type edit page.
 *
 * @package Xama
 */

namespace Xama\MetaBoxes;

use Xama\Posts\Quiz;
use Xama\Posts\Question;
use Xama\Core\Settings;
use Xama\Abstracts\MetaBox;

/**
 * Questions class.
 */
class Questions extends MetaBox {
	/**
	 * Meta box name.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public static $name = 'xama_mb_questions';

	/**
	 * Return meta box heading.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_heading(): string {
		return 'Quiz Questions';
	}

	/**
	 * Return parent post type.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_post_type(): string {
		return Quiz::$name;
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
			</section>',
			$this->get_button(),
			$this->get_questions()
		);
	}

	/**
	 * Get Add New Question Button.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function get_button(): string {
		$url = sprintf(
			'%1$s/wp-admin/post-new.php?post_type=%3$s&quiz_id=%2$s',
			home_url(),
			absint( $this->post->ID ),
			esc_html( Question::$name )
		);

		return sprintf(
			'<div>
				<a href="%1$s"
					class="button button-primary button-large"
					style="margin-top: 5px;"
				>%2$s</a>
			</div>',
			esc_url( $url ),
			esc_html__( 'Add New Question', Settings::DOMAIN )
		);
	}

	/**
	 * Get Quiz Questions.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function get_questions(): string {
		$questions = get_posts(
			[
				'post_type'      => Question::$name,
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'meta_key'       => 'xama_quiz_id',
				'meta_value'     => $this->post->ID,
				'order'          => 'ASC',
			]
		);

		$exam = '';

		foreach ( $questions as $question ) {
			$this->id    = $question->ID;
			$this->title = $question->post_title;
			$this->url   = sprintf(
				'%1$s/wp-admin/post.php?post=%2$s&action=edit',
				home_url(),
				absint( $this->id )
			);

			$exam .= sprintf(
				'<li class="xama_admin_question">
					<a href="%1$s" style="text-decoration: none; margin-bottom: 20px; display: block;">
						<h2 style="color: rebeccapurple;">
							<strong>%2$s</strong>
							<span class="dashicons dashicons-edit" style="float: right;"></span>
						</h2>
						<ol style="color: #000;">%3$s</ol>
					</a>
				</li>',
				esc_url( $this->url ),
				esc_html( $this->title ),
				$this->get_options()
			);
		}

		return sprintf(
			'<ul>
				%1$s
			</ul>',
			$exam
		);
	}

	/**
	 * Get Question options.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function get_options(): string {
		$i       = 1;
		$options = '';

		while ( $i < 5 ) {
			$options .= sprintf(
				'<li>
					%1$s
				</li>',
				esc_html( get_post_meta( $this->id, 'xama_option_' . $i, true ) )
			);
			++$i;
		}

		return $options;
	}

	/**
	 * Save Meta box.
	 *
	 * @param int      $post_id Post ID.
	 * @param \WP_Post $post WP Post.
	 * @return void
	 */
	public function save_meta_box( $post_id, $post ): void {
		// Nothing to do for now...
	}
}
