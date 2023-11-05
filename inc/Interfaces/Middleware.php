<?php
/**
 * Middleware Interface.
 *
 * This interface defines a contract for middleware
 * classes and defines common methods that derived classes
 * should implement.
 *
 * @package Xama
 */

namespace Xama\Interfaces;

interface Middleware {
	/**
	 * Authenticate user.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function authenticate(): void;

	/**
	 * On Login.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function login(): void;

	/**
	 * On Logout.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function logout(): void;

	/**
	 * On Redirect.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function redirect(): void;
}
