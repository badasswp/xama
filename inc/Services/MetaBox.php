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
use Xama\Abstracts\Service;
use Xama\Interfaces\Registrable;

class MetaBox extends Service implements Registrable {
	/**
	 * Set up.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->meta_boxes = [
			Answer::class,
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
		add_action( 'add_meta_boxes', [ $this, 'register_meta_boxes' ] );

		foreach ( $this->meta_boxes as $class ) {
			$object = new $class();
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
		/**
		 * Filter list of meta boxes.
		 *
		 * @since 1.0.0
		 *
		 * @param array $meta_boxes Meta boxes.
		 * @return array
		 */
		$this->meta_boxes = (array) apply_filters( 'xama_meta_boxes', $this->meta_boxes );

		foreach ( $this->meta_boxes as $class ) {
			if ( ! class_exists( $class ) ) {
				throw new \LogicException( $class . ' does not exist.' );
			}
			( new $class() )->register_meta_box();
		}
	}
}
