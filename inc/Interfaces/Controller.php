<?php
/**
 * Controller Interface.
 *
 * This interface defines a contract for HTTP
 * GET & POST requests and defines common methods
 * that derived classes should implement.
 *
 * @package Xama
 */

namespace Xama\Interfaces;

interface Controller {
	/**
	 * Run logic.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function run();
}
