<?php
/**
 * Post type abstraction.
 *
 * This abstract class serves as a base handler for registering
 * custom post types in the Xama plugin. It provides a standardized
 * structure and common methods for managing custom post types.
 *
 * @package Xama
 */

namespace Xama\Abstracts;

use Xama\Core\Settings;

/**
 * Post class.
 */
abstract class Post {
	/**
	 * Set up.
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
	 * Get post type name.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_name(): string {
		return static::$name;
	}

	/**
	 * Get singular label for post type.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	abstract public function get_singular_label(): string;

	/**
	 * Get plural label for post type.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	abstract public function get_plural_label(): string;

	/**
	 * Get supports for post type.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	abstract public function get_supports(): array;

	/**
	 * Save post type.
	 *
	 * @since 1.0.0
	 *
	 * @param int      $post_id Post ID.
	 * @param \WP_Post $post WP Post.
	 * @return void
	 */
	abstract public function save_post_type( $post_id, $post ): void;

	/**
	 * Register Post columns.
	 *
	 * @since 1.0.0
	 *
	 * @param array $columns Post columns.
	 * @return array
	 */
	abstract public function register_post_columns( $columns ): array;

	/**
	 * Register Post column data.
	 *
	 * @since 1.0.0
	 *
	 * @param array $column Column names.
	 * @param int   $post_id Post ID.
	 * @return void
	 */
	abstract public function register_post_column_data( $column, $post_id ): void;

	/**
	 * Is Post visible in REST.
	 *
	 * @since 1.0.0
	 *
	 * @return boolean
	 */
	abstract public function is_post_visible_in_rest(): bool;

	/**
	 * Register post type.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_post_type(): void {
		if ( ! post_type_exists( $this->get_name() ) ) {
			register_post_type( $this->get_name(), $this->get_options() );
		}
	}

	/**
	 * Return post type options.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_options(): array {
		/**
		 * Filter Post options.
		 *
		 * @since 1.0.0
		 *
		 * @param array Post options.
		 * @return array
		 */
		return (array) apply_filters(
			'xama_post_options',
			[
				'labels'       => $this->get_labels(),
				'public'       => true,
				'has_archive'  => true,
				'show_in_menu' => Settings::DOMAIN,
				'supports'     => $this->get_supports(),
				'show_in_rest' => $this->is_post_visible_in_rest(),
				'rewrite'      => [
					'slug' => ( method_exists( $this, 'url_slug' ) ) ? $this->url_slug() : '',
				],
			]
		);
	}

	/**
	 * Get labels for post type.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_labels(): array {
		$singular_label = $this->get_singular_label();
		$plural_label   = $this->get_plural_label();

		$labels = [
			'name'          => sprintf(
				'%1$s',
				__( $plural_label, Settings::DOMAIN ),
			),
			'singular_name' => sprintf(
				'%1$s',
				__( $singular_label, Settings::DOMAIN ),
			),
			'add_new'       => sprintf(
				'%1$s',
				__( "Add New {$singular_label}", Settings::DOMAIN ),
			),
			'add_new_item'  => sprintf(
				'%1$s',
				__( "Add New {$singular_label}", Settings::DOMAIN ),
			),
			'new_item'      => sprintf(
				'%1$s',
				__( "New {$singular_label}", Settings::DOMAIN ),
			),
			'edit_item'     => sprintf(
				'%1$s',
				__( "Edit {$singular_label}", Settings::DOMAIN ),
			),
			'view_item'     => sprintf(
				'%1$s',
				__( "View {$singular_label}", Settings::DOMAIN ),
			),
			'search_items'  => sprintf(
				'%1$s',
				__( "Search {$plural_label}", Settings::DOMAIN ),
			),
			'menu_name'     => sprintf(
				'%1$s',
				__( $plural_label, Settings::DOMAIN ),
			),
		];

		return $labels;
	}

	/**
	 * Get Permalink.
	 *
	 * @since 1.0.0
	 *
	 * @param int $post_id Post ID.
	 * @return string
	 */
	public function get_permalink( $post_id ): string {
		return sprintf(
			'<a target="_blank" href="%1$s">
				%2$s
			</a>',
			esc_attr( get_permalink( $post_id ) ),
			esc_html( get_permalink( $post_id ) )
		);
	}

	/**
	 * Get Posts.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function get_posts(): array {
		return get_posts(
			[
				'post_type'      => static::$name,
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => 'date',
			]
		);
	}

	/**
	 * Get number of Posts.
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public static function get_number_of_posts(): int {
		return count( static::get_posts() );
	}

	/**
	 * Get most recent Post.
	 *
	 * @since 1.0.0
	 *
	 * @return \WP_Post
	 */
	public static function get_most_recent_post() {
		return ( static::get_posts() )[0];
	}
}
