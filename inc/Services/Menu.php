<?php
/**
 * Menu Service.
 *
 * This service is responsible for managing the menu
 * in the WordPress admin dashboard for the plugin. It
 * registers a custom menu page and displays features.
 *
 * @package Xama
 */

namespace Xama\Services;

use Xama\Core\Settings;
use Xama\Abstracts\Service;
use Xama\Interfaces\Registrable;

class Menu extends Service implements Registrable {
	/**
	 * Bind to WP.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'admin_menu', [ $this, 'register_menu' ] );
	}

	/**
	 * Register Post type implementation.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_menu() {
		add_menu_page(
			__( Settings::NAME, Settings::DOMAIN ),
			__( Settings::NAME, Settings::DOMAIN ),
			Settings::ROLE,
			Settings::DOMAIN,
			false,
			'dashicons-welcome-learn-more',
			99
		);
	}
}
