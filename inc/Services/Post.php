<?php
/**
 * Post Service.
 *
 * This service manages custom post types within the Xama
 * plugin. It provides functionality for registering and binding
 * custom post types to WordPress.
 *
 * @package Xama
 */

namespace Xama\Services;

use Xama\Posts\Quiz;
use Xama\Posts\Score;
use Xama\Posts\Question;
use Xama\Abstracts\Service;
use Xama\Interfaces\Registrable;

class Post extends Service implements Registrable {
	/**
	 * Set up.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->post_types = [
			Quiz::class,
			Question::class,
			Score::class,
		];
	}

	/**
	 * Bind to WP.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'init', [ $this, 'register_post_types' ] );

		foreach ( $this->post_types as $class ) {
			add_filter( 'manage_' . $class::$name . '_posts_columns', [ new $class(), 'register_post_columns' ], 10, 2 );
			add_action( 'manage_' . $class::$name . '_posts_custom_column', [ new $class(), 'register_post_column_data' ], 10, 2 );
			add_action( 'publish_' . $class::$name, [ new $class(), 'save_post_type' ], 10, 2 );
		}
	}

	/**
	 * Register Post type implementation.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_post_types() {
		/**
		 * Filter list of post types.
		 *
		 * @since 1.0.0
		 *
		 * @param array $post_types Post types.
		 * @return array
		 */
		$this->post_types = (array) apply_filters( 'xama_post_types', $this->post_types );

		foreach ( $this->post_types as $class ) {
			if ( ! class_exists( $class ) ) {
				throw new \LogicException( $class . ' does not exist.' );
			}
			( new $class() )->register_post_type();
		}
	}

	/**
	 * Flush Rewrite Cache.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function rewrite_flush(): void {
		flush_rewrite_rules();
	}
}
