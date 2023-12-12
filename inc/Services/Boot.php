<?php
/**
 * Boot Service.
 *
 * This service is responsible for loading base, language, user settings
 * used in other areas of the plugin. By default, this binds to the
 * "init" hook of WP.
 *
 * @package Xama
 */

namespace Xama\Services;

use Xama\Core\Settings;
use Xama\Abstracts\Service;
use Xama\Interfaces\Registrable;

class Boot extends Service implements Registrable {
	/**
	 * Language DIR.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private string $domain;

	/**
	 * Set up.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->domain = dirname( plugin_basename( __FILE__ ) ) . '/../../languages';
	}

	/**
	 * Bind to WP.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'init', [ $this, 'register_plugin_domain' ] );
		add_action( 'init', [ $this, 'register_plugin_role' ] );
	}

	/**
	 * Register Plugin's textdomain.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_plugin_domain(): void {
		load_plugin_textdomain( Settings::DOMAIN, false, $this->domain );
	}

	/**
	 * Register Plugin's user role.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_plugin_role(): void {
		if ( ! is_null( get_role( Settings::SLUG ) ) ) {
			return;
		}

		add_role(
			Settings::SLUG,
			Settings::NAME,
			[
				'read'               => true,
				'edit_posts'         => false,
				'upload_files'       => false,
				'manage_categories'  => false,
				'edit_others_posts'  => false,
				'delete_posts'       => false,
				'edit_theme_options' => false,
				'install_plugins'    => false,
				'edit_users'         => false,
				'edit_plugins'       => false,
			]
		);
	}
}
