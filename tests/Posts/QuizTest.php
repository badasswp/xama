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
			->with( [ 'title', 'editor' ] )
			->reply( [ 'title', 'editor' ] );

		$supports = $this->post->get_supports();

		$this->assertSame( $supports, [ 'title', 'editor' ] );
		$this->assertConditionsMet();
	}
}
