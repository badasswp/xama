<?php
/**
 * Boot Service.
 *
 * This service is responsible for loading base, language settings
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
}
