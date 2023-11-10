<?php
/**
 * MetaBox Service.
 *
 * This service manages custom meta boxes within the Xama
 * plugin. It provides functionality for registering and binding
 * custom meta boxes to CPTs in WordPress.
 *
 * @package Xama
 */

namespace Xama\Services;

use Xama\MetaBoxes\Answer;
use Xama\MetaBoxes\Scores;
use Xama\MetaBoxes\Options;
use Xama\MetaBoxes\Questions;
use Xama\Abstracts\Service;
use Xama\Interfaces\Registrable;

class MetaBox extends Service implements Registrable {
	/**
	 * Set up.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$meta_boxes = [
			Answer::class,
			Options::class,
			Questions::class,
			Scores::class,
		];

		/**
		 * Filter list of meta boxes.
		 *
		 * @since 1.0.0
		 *
		 * @param array $meta_boxes Meta boxes.
		 * @return array
		 */
		$meta_boxes = (array) apply_filters( 'xama_meta_boxes', $meta_boxes );

		foreach ( $meta_boxes as $class ) {
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
		add_action( 'add_meta_boxes', [ $this, 'register_meta_boxes' ] );

		foreach ( $this->objects as $object ) {
			add_action( 'publish_' . $object->get_post_type(), [ $object, 'save_meta_box' ], 10, 2 );
		}
	}

	/**
	 * Register Meta box implementation.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_meta_boxes() {
		foreach ( $this->objects as $object ) {
			 $object->register_meta_box();
		}
	}
}
