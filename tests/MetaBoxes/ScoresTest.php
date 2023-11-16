<?php

namespace Xama\Tests\MetaBoxes;

use Mockery;
use stdClass;
use Xama\Core\Settings;
use WP_Mock\Tools\TestCase;
use Xama\MetaBoxes\Scores;

/**
 * @covers Scores
 */
class ScoresTest extends TestCase {
	private $scores;

	public function setUp(): void {
		\WP_Mock::setUp();

		$this->scores = new Scores();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_get_name() {
		$name = Scores::$name;

		$this->assertSame( $name, 'xama_mb_scores' );
		$this->assertConditionsMet();
	}

	public function test_get_heading() {
		$heading = $this->scores->get_heading();

		$this->assertSame( $heading, 'Quiz Scores' );
		$this->assertConditionsMet();
	}

	public function test_get_post_type() {
		$post_type = $this->scores->get_post_type();

		$this->assertSame( $post_type, 'xama_score' );
		$this->assertConditionsMet();
	}

	public function test_get_position() {
		$position = $this->scores->get_position();

		$this->assertSame( $position, '' );
		$this->assertConditionsMet();
	}

	public function test_get_priority() {
		$priority = $this->scores->get_priority();

		$this->assertSame( $priority, 'high' );
		$this->assertConditionsMet();
	}
}
