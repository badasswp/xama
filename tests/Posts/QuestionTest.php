<?php

namespace Xama\Tests\Posts;

use stdClass;
use Xama\Core\Settings;
use Xama\Posts\Question;
use WP_Mock\Tools\TestCase;

/**
 * @covers Question
 */
class QuestionTest extends TestCase {
	private $post;

	public function setUp(): void {
		\WP_Mock::setUp();

		$this->post = new Question();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_get_name() {
		$name = Question::$name;

		$this->assertSame( $name, 'xama_question' );
		$this->assertConditionsMet();
	}

	public function test_get_singular_label() {
		$singular_label = $this->post->get_singular_label();

		$this->assertSame( $singular_label, 'Question' );
		$this->assertConditionsMet();
	}

	public function test_get_plural_label() {
		$plural_label = $this->post->get_plural_label();

		$this->assertSame( $plural_label, 'Questions' );
		$this->assertConditionsMet();
	}

	public function test_get_supports() {
		\WP_Mock::onFilter( 'xama_question_supports' )
			->with( [ 'title', 'thumbnail' ] )
			->reply( [ 'title', 'thumbnail' ] );

		$supports = $this->post->get_supports();

		$this->assertSame( $supports, [ 'title', 'thumbnail' ] );
		$this->assertConditionsMet();
	}

	public function test_save_post_type() {
		$post = $this->getMockBuilder( '\WP_Post' )
					->disableOriginalConstructor()
					->getMock();

		$post->ID = 1;

		\WP_Mock::expectAction( 'xama_question_save', $post->ID, $post );

		$supports = $this->post->save_post_type( $post->ID, $post );

		$this->assertInstanceOf( '\WP_Post', $post );
		$this->assertConditionsMet();
	}

	public function test_register_post_column_data() {
		$post   = new stdClass();
		$column = 'quiz';

		$post->ID         = 1;
		$post->post_title = 'What is WordPress?';

		\WP_Mock::userFunction( 'get_post_meta' )
			->once()
			->with( 1, 'xama_quiz_id', true )
			->andReturn( 1 );

		\WP_Mock::userFunction( 'get_post_meta' )
			->once()
			->with( 1, 'xama_answer', true )
			->andReturn( 'A' );

		\WP_Mock::userFunction( 'get_the_title' )
			->once()
			->with( 1 )
			->andReturn( 'WordPress Test Quiz' );

		\WP_Mock::userFunction( 'esc_html' )
			->once()
			->with( 'WordPress Test Quiz' )
			->andReturn( 'WordPress Test Quiz' );

		\WP_Mock::expectAction( 'xama_question_column_data', 'quiz', 1 );

		$this->post->register_post_column_data( $column, $post->ID );

		$this->expectOutputString( 'WordPress Test Quiz' );
		$this->assertConditionsMet();
	}

	public function test_register_post_columns() {
		$columns = [
			'quiz'   => 'Quiz',
			'answer' => 'Answer',
			'passed' => 'People Passed (%)',
			'date'   => 'Date',
		];

		\WP_Mock::userFunction(
			'esc_html__',
			[
				'return' => function ( $column, $domain = Settings::DOMAIN ) {
					return $column;
				},
			]
		);

		\WP_Mock::expectFilter( 'xama_question_columns', $columns );

		$columns = $this->post->register_post_columns( $columns );

		$this->assertSame(
			$columns,
			[
				'quiz'   => 'Quiz',
				'answer' => 'Answer',
				'passed' => 'People Passed (%)',
				'date'   => 'Date',
			]
		);
		$this->assertConditionsMet();
	}

	public function test_url_slug() {
		$slug = $this->post->url_slug();

		$this->assertSame( $slug, 'question' );
		$this->assertConditionsMet();
	}

	public function test_is_post_visible_in_rest() {
		\WP_Mock::expectFilter( 'xama_question_visible_in_rest', false );

		$this->post->is_post_visible_in_rest();

		$this->assertConditionsMet();
	}
}
