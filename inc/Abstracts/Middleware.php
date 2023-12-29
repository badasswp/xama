<?php
/**
 * Middleware abstraction.
 *
 * This abstract class serves as the foundation for creating
 * middleware classes responsible for authentication. It defines
 * common methods that derived classes should implement.
 *
 * @package Xama
 */

namespace Xama\Abstracts;

/**
 * Middleware class.
 */
abstract class Middleware implements \Xama\Interfaces\Middleware {
	/**
	 * Authenticate user.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	abstract public function authenticate(): void;

	/**
	 * Redirect user.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $exit true/false.
	 * @return void
	 */
	public function redirect( $exit = true ): void {
		$this->logout();

		// Get Page...
		$page = get_page_by_path( $this->redirect_page, OBJECT, 'page' );

		// Get URL...
		$url = get_permalink( $page->ID );

		if ( $this->referrer && preg_match( '/[0-9]+/', $this->referrer, $matches ) ) {
			$url .= '?id=' . $this->referrer;
		}

		// Redirect User...
		wp_redirect( $url );

		// Exit WP execution...
		$this->exit = ! isset( $this->exit ) ? $exit : $this->exit;

		if ( $this->exit ) {
			exit;
		}
	}

	/**
	 * On Login.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	abstract public function login(): void;

	/**
	 * On Logout.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	abstract public function logout(): void;
}
