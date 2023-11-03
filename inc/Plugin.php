<?php
/**
 * Main Plugin class.
 *
 * This class represents the core of the Xama plugin.
 * It initializes the plugin, manages the singleton instance,
 * and runs the plugin by setting up various services.
 *
 * @package Xama
 */

namespace Xama;

use Xama\Core\Container;

/**
 * Plugin class.
 */
class Plugin {
	/**
	 * Singleton Instance.
	 *
	 * @since 1.0.0
	 *
	 * @var \Plugin
	 */
	protected static $instance;

	/**
	 * Get Class instance.
	 *
	 * @since 1.0.0
	 *
	 * @return \Plugin
	 */
	public static function get_instance(): Plugin {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Init method.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function init(): void {
		( new Container() )->register();
	}

	/**
	 * Run Plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function run(): void {
		$this->init();

		/**
		 * Fires after Plugin is loaded.
		 *
		 * @since 1.0.0
		 */
		do_action( 'xama_after_init' );
	}
}
