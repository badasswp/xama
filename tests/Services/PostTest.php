<?php

namespace Xama\Tests\Services;

use stdClass;
use Xama\Core\Settings;
use Xama\Services\Post;
use WP_Mock\Tools\TestCase;

use Xama\Posts\Quiz;
use Xama\Posts\Question;
use Xama\Posts\Score;

/**
 * @covers Post
 */
class PostTest extends TestCase {
	private $post;

	public function setUp(): void {
		\WP_Mock::setUp();

		\WP_Mock::expectFilter(
			'xama_post_types',
			[
				Quiz::class,
				Question::class,
				Score::class,
			]
		);

		$this->post = new Post();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}
}
