<?php

namespace Xama\Tests\Abstracts;

use stdClass;
use Xama\Core\Settings;
use Xama\Abstracts\Controller;
use WP_Mock\Tools\TestCase;

/**
 * @covers Controller
 */
class ControllerTest extends TestCase {
	private $metabox;

	public function setUp(): void {
		\WP_Mock::setUp();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}
}

class ConcreteController extends Controller {
	public function run(): void {}
}
