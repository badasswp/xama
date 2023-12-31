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
				.post-type-xama_quiz.post-php .wrap .wp-heading-inline+.page-title-action,
				.post-type-xama_question .wrap .wp-heading-inline+.page-title-action,
				.post-type-xama_question #edit-slug-box,
				.post-type-xama_question #message a,
				.post-type-xama_score .wrap .wp-heading-inline+.page-title-action,
				.post-type-xama_score #edit-slug-box,
				.post-type-xama_score #message a {
					display: none;
				}

				.post-type-xama_quiz #wp-content-editor-tools,
				.post-type-xama_question #wp-content-editor-tools,
				.post-type-xama_score #wp-content-editor-tools {
					background: none;
				}

				.post-type-xama_quiz #post-body-content,
				.post-type-xama_question #post-body-content,
				.post-type-xama_score #post-body-content {
					margin-bottom: 0;
				}

				.post-type-xama_quiz #titlediv,
				.post-type-xama_question #titlediv,
				.post-type-xama_score #titlediv {
					margin-bottom: 20px;
				}

				.post-type-xama_quiz #xama-banner {
					padding: 10px 20px;
					margin-top: 15px;
				}

				.post-type-xama_quiz #xama-banner .dashicons-welcome-learn-more:before {
					font-size: 28px;
					margin-right: 10px;
				}

				.post-type-xama_quiz #xama-banner h1 {
					font-size: 20px;
				}

				.post-type-xama_quiz #xama-banner p {
					font-size: 14px;
				}

				.post-type-xama_quiz #xama-banner #button {
					margin-top: 15px;
				}
			</style>',
			esc_attr( 'text/css' )
		);
	}
}
