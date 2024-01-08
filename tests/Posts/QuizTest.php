<?php

namespace Xama\Tests\Posts;

use Mockery;
use stdClass;
use Xama\Posts\Quiz;
use Xama\Core\Settings;
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

	public function test_get_name() {
		$name = Quiz::$name;

		$this->assertSame( $name, 'xama_quiz' );
		$this->assertConditionsMet();
	}

	public function test_get_singular_label() {
		$singular_label = $this->post->get_singular_label();

		$this->assertSame( $singular_label, 'Quiz' );
		$this->assertConditionsMet();
	}

	public function test_get_plural_label() {
		$plural_label = $this->post->get_plural_label();

		$this->assertSame( $plural_label, 'Quizzes' );
		$this->assertConditionsMet();
	}

	public function test_get_supports() {
		\WP_Mock::onFilter( 'xama_quiz_supports' )
			->with( [ 'title' ] )
			->reply( [ 'title' ] );

		$supports = $this->post->get_supports();

		$this->assertSame( $supports, [ 'title' ] );
		$this->assertConditionsMet();
	}

	public function test_save_post_type() {
		$post = $this->getMockBuilder( '\WP_Post' )
					->disableOriginalConstructor()
					->getMock();

		$post->ID = 1;

		\WP_Mock::userFunction( 'flush_rewrite_rules' )
			->once();

		\WP_Mock::expectAction( 'xama_quiz_save', $post->ID, $post );

		$supports = $this->post->save_post_type( $post->ID, $post );

		$this->assertInstanceOf( '\WP_Post', $post );
		$this->assertConditionsMet();
	}

	public function test_register_post_column_data() {
		$column = 'questions';
		$post   = new stdClass();

		$post->ID         = 1;
		$post->post_title = 'What a Wonderful World?';

		\WP_Mock::userFunction( 'wp_cache_get' )
			->once()
			->with( 'xama_cache_questions_1' )
			->andReturn( [ $post ] );

		\WP_Mock::userFunction( 'esc_html' )
			->once()
			->with( 1 )
			->andReturn( 1 );

		\WP_Mock::expectAction( 'xama_quiz_column_data', 'questions', 1 );

		$this->post->register_post_column_data( $column, 1 );

		$this->expectOutputString( 1 );
		$this->assertConditionsMet();
	}

	public function test_register_post_column_for_url() {
		$column = 'url';
		$url    = 'https://example.com/quiz/url';

		\WP_Mock::userFunction( 'wp_kses' )
			->once()
			->with(
				'<a href="' . $url . '">' . $url . '</a>',
				[
					'a' => [
						'href' => [],
					],
				]
			)
			->andReturn( '<a href="' . $url . '">' . $url . '</a>' );

		\WP_Mock::expectAction( 'xama_quiz_column_data', 'url', 1 );

		$mock = Mockery::mock( Quiz::class )->makePartial();

		$mock->shouldReceive( 'get_permalink' )
			->once()
			->with( 1 )
			->andReturn( '<a href="' . $url . '">' . $url . '</a>' );

		$mock->register_post_column_data( $column, 1 );

		$this->expectOutputString(
			'<a href="https://example.com/quiz/url">https://example.com/quiz/url</a>'
		);
		$this->assertConditionsMet();
	}

	public function test_register_post_columns() {
		$columns = [
			'name'      => 'Name',
			'url'       => 'URL',
			'questions' => 'Number of Questions',
			'date'      => 'Date',
		];

		\WP_Mock::userFunction(
			'esc_html__',
			[
				'return' => function ( $column, $domain = Settings::DOMAIN ) {
					return $column;
				},
			]
		);

		\WP_Mock::expectFilter( 'xama_quiz_columns', $columns );

		$columns = $this->post->register_post_columns( $columns );

		$this->assertSame(
			$columns,
			[
				'name'      => 'Name',
				'url'       => 'URL',
				'questions' => 'Number of Questions',
				'date'      => 'Date',
			]
		);
		$this->assertConditionsMet();
	}

	public function test_url_slug() {
		$slug = $this->post->url_slug();

		$this->assertSame( $slug, 'quiz' );
		$this->assertConditionsMet();
	}

	public function test_is_post_visible_in_rest() {
		\WP_Mock::expectFilter( 'xama_quiz_visible_in_rest', false );

		$this->post->is_post_visible_in_rest();

		$this->assertConditionsMet();
	}
}
