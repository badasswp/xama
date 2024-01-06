<?php
/**
 * Editor Service.
 *
 * This class is responsible for customizing
 * the Editor used in the Plugin's custom post type
 * WP admin pages.
 *
 * @package Xama
 */

namespace Xama\Services;

use Xama\Posts\Quiz;
use Xama\Posts\Question;

use Xama\Core\Settings;
use Xama\Abstracts\Service;
use Xama\Interfaces\Registrable;

class Editor extends Service implements Registrable {
	/**
	 * Plugin Post types.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public array $post_types;

	/**
	 * Set up.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->post_types = [
			Quiz::$name     => 'Quiz',
			Question::$name => 'Question',
		];

		/**
		 * Filter list of post_types.
		 *
		 * @since 1.0.0
		 *
		 * @param array $post_types Plugin post types.
		 * @return array
		 */
		$this->post_types = (array) apply_filters( 'xama_editor_post_types', $this->post_types );
	}

	/**
	 * Bind to WP.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'edit_form_after_title', [ $this, 'register_editor_position' ] );
	}

	/**
	 * Register Editor position below all other metaboxes.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_Post $post WP Post object.
	 * @return void
	 */
	public function register_editor_position( $post ): void {
		global $wp_meta_boxes;

		if ( ! in_array( $post->post_type, array_keys( $this->post_types ), true ) ) {
			return;
		}

		// Show plugin's metaboxes first...
		do_meta_boxes( get_current_screen(), 'advanced', $post );

		// Unset global metaboxes to prevent re-display
		unset( $wp_meta_boxes[ $post->post_type ]['advanced'] );

		// Now, show Custom Editor...
		add_meta_box(
			'postdiv',
			esc_html__( $this->post_types[ $post->post_type ] . ' ' . 'Instructions', Settings::DOMAIN ),
			[ $this, 'register_custom_editor' ],
			$post->post_type,
			'normal',
			'core'
		);
	}

	/**
	 * Callback to display the editor box after other metaboxes.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_Post $post WP Post object.
	 * @param array    $box  Metabox args.
	 * @return void
	 */
	public function register_custom_editor( $post, $box ) {
		$content = htmlspecialchars_decode( $post->post_content );

		printf( '%1$s', wp_editor( $content, 'content', [ 'textarea_name' => 'content' ] ) );
	}
}
