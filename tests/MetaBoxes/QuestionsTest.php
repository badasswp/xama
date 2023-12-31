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

		$this->metabox = new Questions();

		$post     = new stdClass();
		$post->ID = 1;

		$this->metabox->post = $post;
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_get_name() {
		$name = Questions::$name;

		$this->assertSame( $name, 'xama_mb_questions' );
		$this->assertConditionsMet();
	}

	public function test_get_heading() {
		$heading = $this->metabox->get_heading();

		$this->assertSame( $heading, 'Quiz Questions' );
		$this->assertConditionsMet();
	}

	public function test_get_post_type() {
		$post_type = $this->metabox->get_post_type();

		$this->assertSame( $post_type, 'xama_quiz' );
		$this->assertConditionsMet();
	}

	public function test_get_position() {
		$position = $this->metabox->get_position();

		$this->assertSame( $position, '' );
		$this->assertConditionsMet();
	}

	public function test_get_priority() {
		$priority = $this->metabox->get_priority();

		$this->assertSame( $priority, 'high' );
		$this->assertConditionsMet();
	}

	public function test_get_button() {
		\WP_Mock::userFunction( 'home_url' )
			->once()
			->andReturn( 'http://example.com' );

		\WP_Mock::userFunction( 'absint' )
			->once()
			->with( 1 )
			->andReturn( '1' );

		\WP_Mock::userFunction( 'esc_html' )
			->once()
			->with( 'xama_question' )
			->andReturn( 'xama_question' );

		\WP_Mock::userFunction( 'esc_url' )
			->once()
			->with( 'http://example.com/wp-admin/post-new.php?post_type=xama_question&quiz_id=1' )
			->andReturn( 'http://example.com/wp-admin/post-new.php?post_type=xama_question&quiz_id=1' );

		\WP_Mock::userFunction( 'esc_html__' )
			->once()
			->with( 'Add New Question', Settings::DOMAIN )
			->andReturn( 'Add New Question' );

		$reflection = new \ReflectionClass( $this->metabox );
		$method     = $reflection->getMethod( 'get_button' );
		$method->setAccessible( true );

		$expected = '<div>
				<a href="http://example.com/wp-admin/post-new.php?post_type=xama_question&quiz_id=1"
					class="button button-primary button-large"
					style="margin-top: 5px;"
				>Add New Question</a>
			</div>';

		$button = $method->invoke( $this->metabox );

		$this->assertSame( $expected, $button );
		$this->assertConditionsMet();
	}

	public function test_get_questions() {
		$post = new stdClass();

		$post->ID         = 1;
		$post->post_title = 'What is a Butterfly?';

		$posts = [
			$post,
		];

		\WP_Mock::userFunction( 'get_posts' )
			->once()
			->andReturn( $posts );

		\WP_Mock::userFunction( 'home_url' )
			->once()
			->andReturn( 'http://example.com' );

		\WP_Mock::userFunction( 'absint' )
			->once()
			->with( 1 )
			->andReturn( '1' );

		\WP_Mock::userFunction( 'esc_url' )
			->once()
			->with( 'http://example.com/wp-admin/post.php?post=1&action=edit' )
			->andReturn( 'http://example.com/wp-admin/post.php?post=1&action=edit' );

		\WP_Mock::userFunction( 'esc_html' )
			->once()
			->with( 'What is a Butterfly?' )
			->andReturn( 'What is a Butterfly?' );

		\WP_Mock::userFunction( 'get_post_meta' )
			->once()
			->with( 1, 'xama_options', true )
			->andReturn(
				[
					'Option Value 1',
					'Option Value 2',
					'Option Value 3',
					'Option Value 4',
				]
			);

		\WP_Mock::userFunction( 'esc_html' )
			->times( 4 )
			->andReturnValues(
				[
					'Option Value 1',
					'Option Value 2',
					'Option Value 3',
					'Option Value 4',
				]
			);

		$reflection = new \ReflectionClass( $this->metabox );
		$method     = $reflection->getMethod( 'get_questions' );
		$method->setAccessible( true );

		$expected = '<ul>
				<li class="xama_admin_question">
					<a href="http://example.com/wp-admin/post.php?post=1&action=edit" style="text-decoration: none; margin-bottom: 20px; display: block;">
						<h2 style="color: rebeccapurple;">
							<strong>What is a Butterfly?</strong>
							<span class="dashicons dashicons-edit" style="float: right;"></span>
						</h2>
						<ol style="color: #000;"><li>
					Option Value 1
				</li><li>
					Option Value 2
				</li><li>
					Option Value 3
				</li><li>
					Option Value 4
				</li></ol>
					</a>
				</li>
			</ul>';

		$questions = $method->invoke( $this->metabox );

		$this->assertSame( $expected, $questions );
		$this->assertConditionsMet();
	}

	public function test_get_options() {
		$this->metabox->id = 1;

		\WP_Mock::userFunction( 'get_post_meta' )
			->once()
			->with( 1, 'xama_options', true )
			->andReturn(
				[
					'Option Value 1',
					'Option Value 2',
					'Option Value 3',
					'Option Value 4',
				]
			);

		\WP_Mock::userFunction( 'esc_html' )
			->times( 4 )
			->andReturnValues(
				[
					'Option Value 1',
					'Option Value 2',
					'Option Value 3',
					'Option Value 4',
				]
			);

		$reflection = new \ReflectionClass( $this->metabox );
		$method     = $reflection->getMethod( 'get_options' );
		$method->setAccessible( true );

		$expected = '<li>
					Option Value 1
				</li><li>
					Option Value 2
				</li><li>
					Option Value 3
				</li><li>
					Option Value 4
				</li>';

		$options = $method->invoke( $this->metabox );

		$this->assertSame( $expected, $options );
		$this->assertConditionsMet();
	}
}
