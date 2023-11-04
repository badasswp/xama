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

use Xama\Abstracts\Service;
use Xama\Interfaces\Registrable;

class Template extends Service implements Registrable {
	/**
	 * Set up.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->template = plugin_dir_path( __FILE__ ) . '../Views/index.php';
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
	}

	/**
	 * Register Template implementation.
	 *
	 * @since 1.0.0
	 *
	 * @param string $template WP Template.
	 * @return string
	 */
	public function register_template( $template ): string {
		$post_types = [
			'xama_quiz',
			'xama_question',
			'xama_score',
		];

		if ( ! in_array( get_post_type(), $post_types, true ) ) {
			return $template;
		}

		return $this->template;
	}
}
