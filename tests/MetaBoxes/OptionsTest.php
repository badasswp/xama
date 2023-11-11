<?php

namespace Xama\Tests\MetaBoxes;

use stdClass;
use Xama\Core\Settings;
use Xama\MetaBoxes\Options;
use WP_Mock\Tools\TestCase;

/**
 * @covers Options
 */
class OptionsTest extends TestCase {
	private $options;

	public function setUp(): void {
		\WP_Mock::setUp();

		$this->options = new Options();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_get_name() {
		$name = Options::$name;

		$this->assertSame( $name, 'xama_mb_options' );
		$this->assertConditionsMet();
	}
}
