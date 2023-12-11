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
		\WP_Mock::expectActionAdded( 'manage_xama_quiz_posts_custom_column', [ $this->post->objects[0], 'register_post_column_data' ], 10, 2 );
		\WP_Mock::expectActionAdded( 'publish_xama_quiz', [ $this->post->objects[0], 'save_post_type' ], 10, 2 );

		\WP_Mock::expectFilterAdded( 'manage_xama_question_posts_columns', [ $this->post->objects[1], 'register_post_columns' ], 10, 2 );
		\WP_Mock::expectActionAdded( 'manage_xama_question_posts_custom_column', [ $this->post->objects[1], 'register_post_column_data' ], 10, 2 );
		\WP_Mock::expectActionAdded( 'publish_xama_question', [ $this->post->objects[1], 'save_post_type' ], 10, 2 );

		\WP_Mock::expectFilterAdded( 'manage_xama_score_posts_columns', [ $this->post->objects[2], 'register_post_columns' ], 10, 2 );
		\WP_Mock::expectActionAdded( 'manage_xama_score_posts_custom_column', [ $this->post->objects[2], 'register_post_column_data' ], 10, 2 );
		\WP_Mock::expectActionAdded( 'publish_xama_score', [ $this->post->objects[2], 'save_post_type' ], 10, 2 );

		$this->post->register();

		$this->assertConditionsMet();
	}

	public function test_register_post_types() {
		$labels = [
			'name'          => 'Quizzes',
			'singular_name' => 'Quiz',
			'add_new'       => 'Add New Quiz',
			'add_new_item'  => 'Add New Quiz',
			'new_item'      => 'New Quiz',
			'edit_item'     => 'Edit Quiz',
			'view_item'     => 'View Quiz',
			'search_items'  => 'Search Quizzes',
			'menu_name'     => 'Quizzes',
		];

		\WP_Mock::userFunction( 'post_type_exists' )
			->once()
			->with( 'xama_quiz' )
			->andReturn( false );

		\WP_Mock::userFunction( 'post_type_exists' )
			->once()
			->with( 'xama_question' )
			->andReturn( true );

		\WP_Mock::userFunction( 'post_type_exists' )
			->once()
			->with( 'xama_score' )
			->andReturn( true );

		\WP_Mock::userFunction(
			'__',
			[
				'times' => 9,
				'return' => function( $text ) {
					return $text;
				}
			]
		);

		\WP_Mock::expectFilter(
			'xama_quiz_supports',
			[ 'title', 'editor' ]
		);

		\WP_Mock::expectFilter(
			'xama_quiz_visible_in_rest',
			false
		);

		\WP_Mock::expectFilter(
			'xama_post_options',
			[
				'labels'       => $labels,
				'public'       => true,
				'has_archive'  => true,
				'show_in_menu' => 'xama',
				'supports'     => [ 'title', 'editor' ],
				'show_in_rest' => false,
				'rewrite'      => [
					'slug' => 'quiz'
				],
			]
		);

		\WP_Mock::userFunction( 'register_post_type' )
			->once()
			->with(
				'xama_quiz',
				[
					'labels'       => $labels,
					'public'       => true,
					'has_archive'  => true,
					'show_in_menu' => 'xama',
					'supports'     => [ 'title', 'editor' ],
					'show_in_rest' => false,
					'rewrite'      => [
						'slug' => 'quiz'
					],
				]
			);

		$this->post->register_post_types();

		$this->assertConditionsMet();
	}
}
