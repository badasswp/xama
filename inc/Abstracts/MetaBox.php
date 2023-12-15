<?php
/**
 * Meta box abstraction.
 *
 * This abstract class serves as a base handler for registering
 * meta boxes within the Xama plugin. It provides a foundation
 * for creating and managing meta boxes.
 *
 * @package Xama
 */

namespace Xama\Abstracts;

use Xama\Core\Settings;

/**
 * Meta box class.
 */
abstract class MetaBox {
	/**
	 * Meta box name.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public static $name;

	/**
	 * Abstract Meta box.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 *
	 * @throws \LogicException If $name property is not statically defined within child classes.
	 */
	final public function __construct() {
		if ( ! isset( static::$name ) ) {
			throw new \LogicException( __CLASS__ . ' must define static property name' );
		}
	}

	/**
	 * Get Meta box name.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_name(): string {
		return static::$name;
	}

	/**
	 * Get Meta box heading.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	abstract public function get_heading(): string;

	/**
	 * Get Meta box parent post type.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	abstract public function get_post_type(): string;

	/**
	 * Get Meta box callback.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_Post $post WP Post.
	 * @return void
	 */
	abstract public function get_metabox_callback( $post ): void;

	/**
	 * Get Meta box position.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	abstract public function get_position(): string;

	/**
	 * Get Meta box priority.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	abstract public function get_priority(): string;

	/**
	 * Save Meta box.
	 *
	 * @since 1.0.0
	 *
	 * @param int      $post_id Post ID.
	 * @param \WP_Post $post WP Post.
	 * @return void
	 */
	abstract public function save_meta_box( $post_id, $post ): void;

	/**
	 * Set Visibility of MetaBox.
	 *
	 * @since 1.0.0
	 *
	 * @return boolean
	 */
	public function is_visible(): bool {
		return true;
	}

	/**
	 * Register meta box.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_meta_box(): void {
		/**
		 * Filter Meta Box name, heading, post_type, position, priority.
		 *
		 * @since 1.0.0
		 *
		 * @param array Meta box name, heading, post_type, position, priority.
		 * @return array
		 */
		$meta_box = (array) apply_filters(
			'xama_metabox_options',
			[
				'name'      => $this->get_name(),
				'heading'   => $this->get_heading(),
				'post_type' => $this->get_post_type(),
				'position'  => $this->get_position(),
				'priority'  => $this->get_priority(),
			]
		);

		if ( ! $this->is_visible() ) {
			return;
		}

		add_meta_box(
			$meta_box['name'],
			esc_html__( $meta_box['heading'], Settings::DOMAIN ),
			[ $this, 'get_metabox_callback' ],
			$meta_box['post_type'],
			$meta_box['position'] ?: 'advanced',
			$meta_box['priority'] ?: 'default'
		);
	}
}
