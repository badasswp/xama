<?php
/**
 * Service Class.
 *
 * This abstract class defines a foundation for creating
 * service classes which are singletons, meaning that only
 * one instance of each service class is created.
 *
 * @package Xama
 */

namespace Xama\Abstracts;

use Xama\Interfaces\Registrable;

/**
 * Service class.
 */
abstract class Service implements Registrable {
	/**
	 * Class Instance.
	 *
	 * @since 1.0.0
	 *
	 * @var static[]
	 */
	protected static $instances = [];

	/**
	 * Get instance of Class (Singleton).
	 *
	 * @since 1.0.0
	 *
	 * @return static
	 */
	public static function get_instance() {
		$class = get_called_class();

		if ( ! isset( static::$instances[ $class ] ) ) {
			static::$instances[ $class ] = new static();
		}

		return static::$instances[ $class ];
	}

	/**
	 * Register & bind logic to WP.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	abstract public function register(): void;
}
