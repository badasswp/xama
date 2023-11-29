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
