<?php

namespace Xama\Tests\Helpers;

use stdClass;
use Xama\Posts\Quiz;
use WP_Mock\Tools\TestCase;

require_once __DIR__ . '/../../inc/Helpers/functions.php';

/**
 * @covers Functions
 */
class FunctionsTest extends TestCase {
	public function setUp(): void {
		\WP_Mock::setUp();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}
}
