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

	public function test_get_heading() {
		$heading = $this->options->get_heading();

		$this->assertSame( $heading, 'Question Options' );
		$this->assertConditionsMet();
	}

	public function test_get_post_type() {
		$post_type = $this->options->get_post_type();

		$this->assertSame( $post_type, 'xama_question' );
		$this->assertConditionsMet();
	}

	public function test_get_position() {
		$position = $this->options->get_position();

		$this->assertSame( $position, '' );
		$this->assertConditionsMet();
	}

	public function test_get_priority() {
		$priority = $this->options->get_priority();

		$this->assertSame( $priority, '' );
		$this->assertConditionsMet();
	}
}
