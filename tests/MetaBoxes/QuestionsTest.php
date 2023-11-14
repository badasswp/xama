<?php

namespace Xama\Tests\MetaBoxes;

use Mockery;
use stdClass;
use Xama\Core\Settings;
use WP_Mock\Tools\TestCase;
use Xama\MetaBoxes\Questions;

/**
 * @covers Questions
 */
class QuestionsTest extends TestCase {
	private $questions;

	public function setUp(): void {
		\WP_Mock::setUp();

		$this->questions = new Questions();

		$post     = new stdClass();
		$post->ID = 1;

		$this->questions->post = $post;
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_get_name() {
		$name = Questions::$name;

		$this->assertSame( $name, 'xama_mb_questions' );
		$this->assertConditionsMet();
	}

	public function test_get_heading() {
		$heading = $this->questions->get_heading();

		$this->assertSame( $heading, 'Quiz Questions' );
		$this->assertConditionsMet();
	}

	public function test_get_post_type() {
		$post_type = $this->questions->get_post_type();

		$this->assertSame( $post_type, 'xama_quiz' );
		$this->assertConditionsMet();
	}

	public function test_get_position() {
		$position = $this->questions->get_position();

		$this->assertSame( $position, '' );
		$this->assertConditionsMet();
	}

	public function test_get_priority() {
		$priority = $this->questions->get_priority();

		$this->assertSame( $priority, 'high' );
		$this->assertConditionsMet();
	}
}
