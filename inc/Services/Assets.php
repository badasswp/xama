<?php
/**
 * Assets Service.
 *
 * This class is responsible for loading the Plugin's
 * inline styling. This is purely for the admin/backend
 * and not related to the frontend.
 *
 * @package Xama
 */

namespace Xama\Services;

use Xama\Abstracts\Service;
use Xama\Interfaces\Registrable;

class Assets extends Service implements Registrable {
	/**
	 * Bind to WP.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'admin_head', [ $this, 'register_styles' ] );
	}

	/**
	 * Register Plugin styles.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_styles(): void {
		printf(
			'<style type="%1$s">
				.post-type-xama_question .wrap .wp-heading-inline+.page-title-action,
				.post-type-xama_question #edit-slug-box,
				.post-type-xama_question #message a,
				.post-type-xama_score .wrap .wp-heading-inline+.page-title-action,
				.post-type-xama_score #edit-slug-box,
				.post-type-xama_score #message a {
					display: none;
				}
			</style>',
			esc_attr( 'text/css' )
		);
	}
}
