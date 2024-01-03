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
		add_action( 'init', [ $this, 'register_plugin_pages' ] );
		add_action( 'wp_loaded', [ $this, 'unregister_admin_bar' ] );
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
				'read'                        => true,
				'edit_others_xama_score'      => true,
				'publish_xama_score'          => true,
				'read_xama_score'             => true,
				'edit_xama_score'             => true,
				'edit_published_xama_score'   => true,
				'delete_xama_score'           => true,
				'delete_published_xama_score' => true,
				'create_xama_score'           => true,
			]
		);
	}

	/**
	 * Register Plugin Pages.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_plugin_pages(): void {
		$pages = [
			'login'   => 'Login',
			'sign-up' => 'Sign Up',
		];

		foreach ( $pages as $slug => $title ) {
			$page = get_page_by_path( $slug );

			if ( ! $page ) {
				$args = array(
					'post_title'  => $title,
					'post_name'   => $slug,
					'post_type'   => 'page',
					'post_status' => 'publish',
				);

				$page_id = wp_insert_post( $args );
			}
		}
	}

	/**
	 * Unregister Admin bar.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function unregister_admin_bar(): void {
		if ( is_user_logged_in() && current_user_can( Settings::SLUG ) ) {
			show_admin_bar( false );
		}
	}
}
