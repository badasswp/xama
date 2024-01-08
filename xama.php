<?php
/**
 * Plugin Name: Xama - Quiz, Exam, Poll & Survey Builder.
 * Plugin URI:  https://github.com/badasswp/xama
 * Description: A simple WP plugin to help you set up Quizzes.
 * Version:     1.0.0
 * Author:      AppWorld Team
 * Author URI:  https://xama.ws
 * License:     GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: xama
 * Domain Path: /languages
 *
 * @package Xama
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'XAMA_PLUGIN_AUTOLOAD', __DIR__ . '/vendor/autoload.php' );

/**
 * Checks to see if Composer's autoload file can load.
 *
 * @return bool True or false if it can.
 */
function xama_can_autoload() {
	if ( ! file_exists( XAMA_PLUGIN_AUTOLOAD ) ) {
		error_log(
			sprintf(
				esc_html__( 'Fatal Error: Composer not setup in %', 'xama' ),
				__DIR__
			)
		);
		return false;
	}
	// Require autoload.
	require_once XAMA_PLUGIN_AUTOLOAD;

	return true;
}

/**
 * Generates autoload notices.
 *
 * @return void
 */
function xama_autoload_notice() {
	$class   = 'notice notice-error';
	$message = 'Error: Composer not setup! Please run $ composer install in the Xama plugin directory.';

	// On Composer error, display notice...
	printf(
		'<div class="%1$s">
			<p>%2$s</p>
		</div>',
		esc_attr( $class ),
		esc_html__( $message, 'xama' )
	);
}

/**
 * Run plugin.
 *
 * @return void
 */
function xama_run() {
	if ( xama_can_autoload() ) {
		require_once __DIR__ . '/inc/Helpers/functions.php';
		( \Xama\Plugin::get_instance() )->run();
	} else {
		add_action( 'admin_notices', 'xama_autoload_notice' );
	}
}

xama_run();
