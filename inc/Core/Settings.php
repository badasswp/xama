<?php
/**
 * Plugin Settings.
 *
 * This class provides essential settings and constants
 * for the Xama plugin, making it easier to manage and
 * configure the plugin.
 *
 * @package Xama
 */

namespace Xama\Core;

/**
 * Settings class.
 */
class Settings {
	/**
	 * Plugin name.
	 *
	 * The human-readable name of the Xama plugin.
	 *
	 * @since 1.0.0
	 *
	 * @const string
	 */
	public const NAME = 'Xama';

	/**
	 * Plugin slug.
	 *
	 * The unique slug used to identify the Xama plugin within WordPress.
	 *
	 * @since 1.0.0
	 *
	 * @const string
	 */
	public const SLUG = 'xama';

	/**
	 * Plugin role.
	 *
	 * The minimum user role required to manage Xama plugin options.
	 *
	 * @since 1.0.0
	 *
	 * @const string
	 */
	public const ROLE = 'manage_options';

	/**
	 * Plugin domain.
	 *
	 * The text domain used for internationalization in the Xama plugin.
	 *
	 * @since 1.0.0
	 *
	 * @const string
	 */
	public const DOMAIN = 'xama';

	/**
	 * Plugin options.
	 *
	 * An array of predefined options used in the Xama post types implementation.
	 *
	 * @since 1.0.0
	 *
	 * @const array
	 */
	public const OPTIONS = [
		0 => 'A',
		1 => 'B',
		2 => 'C',
		3 => 'D',
		4 => 'E',
		5 => 'F',
		6 => 'G',
		7 => 'H',
	];

	/**
	 * Plugin bool.
	 *
	 * An array of boolean values represented as strings,
	 * typically used for yes/no choices.
	 *
	 * @since 1.0.0
	 *
	 * @const array
	 */
	public const BOOL = [ 'No', 'Yes' ];
}
