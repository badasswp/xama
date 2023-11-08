<?php

namespace Xama\Tests\MetaBoxes;

use stdClass;
use Xama\Core\Settings;
use Xama\MetaBoxes\Answer;
use WP_Mock\Tools\TestCase;

/**
 * @covers Answer
 */
class AnswerTest extends TestCase {
	private $metabox;

	public function setUp(): void {
		\WP_Mock::setUp();

		$this->metabox = new Answer();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_get_name() {
		$name = Answer::$name;

		$this->assertSame( $name, 'xama_mb_answer' );
		$this->assertConditionsMet();
	}

	public function test_get_heading() {
		$heading = $this->metabox->get_heading();

		$this->assertSame( $heading, 'Question Answer' );
		$this->assertConditionsMet();
	}

	public function test_get_post_type() {
		$post_type = $this->metabox->get_post_type();

		$this->assertSame( $post_type, 'xama_question' );
		$this->assertConditionsMet();
	}

	public function test_get_position() {
		$position = $this->metabox->get_position();

		$this->assertSame( $position, '' );
		$this->assertConditionsMet();
	}

	public function test_get_priority() {
		$priority = $this->metabox->get_priority();

		$this->assertSame( $priority, '' );
		$this->assertConditionsMet();
	}
}
