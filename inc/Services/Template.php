<?php
/**
 * Template Service.
 *
 * This service loads the default template that is responsible
 * for handling the front-end presentational aspects of the plugin,
 * which relies on React for its implementation.
 *
 * @package Xama
 */

namespace Xama\Services;

use Xama\Posts\Quiz;
use Xama\Abstracts\Service;
use Xama\Interfaces\Registrable;

class Template extends Service implements Registrable {
	/**
	 * Plugin Template.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public array $template;

	/**
	 * Set Up.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$path = plugin_dir_path( __FILE__ );

		$this->template = [
			'index'   => $path . '../Views/index.php',
			'login'   => $path . '../Views/login.php',
			'sign-up' => $path . '../Views/sign-up.php',
		];
	}

	/**
	 * Bind to WP.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register(): void {
		add_filter( 'template_include', [ $this, 'register_template' ] );
		add_filter( 'template_include', [ $this, 'register_page_templates' ] );
	}

	/**
	 * Register Template implementation.
	 *
	 * @since 1.0.0
	 *
	 * @param string $wp_template WP Template.
	 * @return string
	 */
	public function register_template( $wp_template ): string {
		if ( get_post_type() !== Quiz::$name || ! file_exists( $this->template['index'] ) ) {
			return $wp_template;
		}

		return $this->template['index'];
	}

	/**
	 * Register Page Templates.
	 *
	 * @since 1.0.0
	 *
	 * @param string $wp_template WP Template.
	 * @return string
	 */
	public function register_page_templates( $wp_template ): string {
		$pages = [ 'login', 'sign-up' ];

		if ( ! is_page( $pages ) ) {
			return $wp_template;
		}

		foreach ( $pages as $page ) {
			if ( is_page( $page ) ) {
				return $this->template[ $page ];
			}
		}
	}
}
