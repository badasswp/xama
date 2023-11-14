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
}
