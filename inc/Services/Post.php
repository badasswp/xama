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
	 * Post Objects
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public array $objects;

	/**
	 * Set up.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$post_types = [
			Quiz::class,
			Question::class,
			Score::class,
		];

		/**
		 * Filter list of post types.
		 *
		 * @since 1.0.0
		 *
		 * @param array $post_types Post types.
		 * @return array
		 */
		$post_types = (array) apply_filters( 'xama_post_types', $post_types );

		foreach ( $post_types as $class ) {
			if ( ! class_exists( $class ) ) {
				throw new \LogicException( $class . ' does not exist.' );
			}
			$this->objects[] = new $class();
		}
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

		foreach ( $this->objects as $object ) {
			add_filter( 'manage_' . $object->get_name() . '_posts_columns', [ $object, 'register_post_columns' ], 10, 2 );
			add_action( 'manage_' . $object->get_name() . '_posts_custom_column', [ $object, 'register_post_column_data' ], 10, 2 );
			add_action( 'publish_' . $object->get_name(), [ $object, 'save_post_type' ], 10, 2 );
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
		foreach ( $this->objects as $object ) {
			$object->register_post_type();
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
