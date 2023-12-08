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

	public function test_register() {
		\WP_Mock::expectActionAdded( 'init', [ $this->post, 'register_post_types' ] );

		\WP_Mock::expectFilterAdded( 'manage_xama_quiz_posts_columns', [ $this->post->objects[0], 'register_post_columns' ], 10, 2 );
		\WP_Mock::expectFilterAdded( 'manage_xama_question_posts_columns', [ $this->post->objects[1], 'register_post_columns' ], 10, 2 );
		\WP_Mock::expectFilterAdded( 'manage_xama_score_posts_columns', [ $this->post->objects[2], 'register_post_columns' ], 10, 2 );

		\WP_Mock::expectActionAdded( 'manage_xama_quiz_posts_custom_column', [ $this->post->objects[0], 'register_post_column_data' ], 10, 2 );
		\WP_Mock::expectActionAdded( 'manage_xama_question_posts_custom_column', [ $this->post->objects[1], 'register_post_column_data' ], 10, 2 );
		\WP_Mock::expectActionAdded( 'manage_xama_score_posts_custom_column', [ $this->post->objects[2], 'register_post_column_data' ], 10, 2 );

		\WP_Mock::expectActionAdded( 'publish_xama_quiz', [ $this->post->objects[0], 'save_post_type' ], 10, 2 );
		\WP_Mock::expectActionAdded( 'publish_xama_question', [ $this->post->objects[1], 'save_post_type' ], 10, 2 );
		\WP_Mock::expectActionAdded( 'publish_xama_score', [ $this->post->objects[2], 'save_post_type' ], 10, 2 );

		$this->post->register();

		$this->assertConditionsMet();
	}
}
