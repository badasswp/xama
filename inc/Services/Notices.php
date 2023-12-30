<?php
/**
 * Notices Service.
 *
 * This class is responsible for loading Notices.
 *
 * @package Xama
 */

namespace Xama\Services;

use Xama\Posts\Quiz;
use Xama\Abstracts\Service;
use Xama\Interfaces\Registrable;

class Notices extends Service implements Registrable {
	/**
	 * Bind to WP.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'admin_notices', [ $this, 'register_notices' ] );
	}

	/**
	 * Register Plugin notices.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_notices(): void {
		$post_type = get_current_screen()->post_type;

		switch ( $post_type ) {
			case Quiz::$name:
				global $pagenow;

				if ( 'edit.php' !== $pagenow ) {
					return;
				}

				if ( ! count( xama_get_quizzies() ) ) {
					echo $this->get_html_notice();
				}

				break;
		}
	}

	/**
	 * HTML Markup.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function get_html_notice(): string {
		$notice = [
			'class'   => 'notice notice-success is-dismissible',
			'title'   => 'Getting Started',
			'content' => 'Thank you for downloading and installing our amazing plugin. To help you get started quickly, create a new Quiz by clicking the button below.',
		];

		return sprintf(
			'<div id="xama-banner" class="%1$s">
				<h1>
					<span class="dashicons-before dashicons-welcome-learn-more"></span>
					%2$s
				</h1>
				<p>%3$s</p>
				<p id="button">
					<a href="%4$s/wp-admin/post-new.php?post_type=xama_quiz" class="button button-primary">Add New Quiz</a>
				</p>
			</div>',
			esc_attr( $notice['class'] ),
			esc_html__( $notice['title'] ),
			esc_html__( $notice['content'] ),
			home_url()
		);
	}
}
