<?php

namespace Xama\Tests\Posts;

use stdClass;
use Xama\Core\Settings;
use Xama\Posts\Quiz;
use WP_Mock\Tools\TestCase;

/**
 * @covers Quiz
 */
class QuizTest extends TestCase {
	private $post;

	public function setUp(): void {
		\WP_Mock::setUp();

		$this->post = new Quiz();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}
}
