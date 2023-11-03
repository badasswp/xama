<?php
/**
 * Bootstrap PHPUnit related dependencies.
 *
 * @package Xama
 */

// First we need to load the composer autoloader, so we can use WP Mock
require_once dirname( __DIR__ ) . '/vendor/autoload.php';

// Bootstrap WP_Mock to initialize built-in features
WP_Mock::activateStrictMode();
WP_Mock::bootstrap();
