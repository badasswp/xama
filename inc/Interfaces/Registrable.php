<?php
/**
 * Register Interface.
 *
 * This interface defines a contract for classes
 * that can be registered and provides a consistent way
 * for different classes to bind logic to WP.
 *
 * @package Xama
 */

namespace Xama\Interfaces;

interface Registrable {
	/**
	 * Register & bind logic to WP.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register(): void;
}
