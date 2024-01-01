<?php

namespace Xama\Tests\MetaBoxes;

use Mockery;
use stdClass;
use Xama\Core\Settings;
use WP_Mock\Tools\TestCase;
use Xama\MetaBoxes\Scores;

/**
 * @covers Scores
 */
class ScoresTest extends TestCase {
	private $scores;

	public function setUp(): void {
		\WP_Mock::setUp();

		$this->metabox = new Scores();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_get_name() {
		$name = Scores::$name;

		$this->assertSame( $name, 'xama_mb_scores' );
		$this->assertConditionsMet();
	}

	public function test_get_heading() {
		$heading = $this->metabox->get_heading();

		$this->assertSame( $heading, 'Quiz Scores' );
		$this->assertConditionsMet();
	}

	public function test_get_post_type() {
		$post_type = $this->metabox->get_post_type();

		$this->assertSame( $post_type, 'xama_score' );
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

	public function test_get_question_options() {
		$this->metabox->id = 1;

		\WP_Mock::userFunction( 'get_post_meta' )
			->once()
			->with( 1, 'xama_options', true)
			->andReturn(
				[
					'Option 1',
					'Option 2',
					'Option 3',
					'Option 4',
				]
			);

		\WP_Mock::userFunction( 'esc_html' )
			->once()
			->with( 'Option 1' )
			->andReturn( 'Option 1' );

		\WP_Mock::userFunction( 'esc_html' )
			->once()
			->with( 'Option 2' )
			->andReturn( 'Option 2' );

		\WP_Mock::userFunction( 'esc_html' )
			->once()
			->with( 'Option 3' )
			->andReturn( 'Option 3' );

		\WP_Mock::userFunction( 'esc_html' )
			->once()
			->with( 'Option 4' )
			->andReturn( 'Option 4' );

		$reflection = new \ReflectionClass( $this->metabox );
		$method     = $reflection->getMethod( 'get_question_options' );
		$method->setAccessible( true );

		$expected = '<li>
					Option 1
				</li><li>
					Option 2
				</li><li>
					Option 3
				</li><li>
					Option 4
				</li>';

		$options = $method->invoke( $this->metabox );

		$this->assertSame( $expected, $options );
		$this->assertConditionsMet();
	}

	public function test_get_scores_heading_labels_and_data() {
		$user             = new stdClass();
		$user->ID         = 1;
		$user->user_login = 'johndoe';
		$user->user_email = 'john@doe.com';

		$this->metabox->scores_auth                          = 1;
		$this->metabox->scores_meta['xama_score_total'][0]   = 100;
		$this->metabox->scores_meta['xama_score_quiz_id'][0] = 1;

		\WP_Mock::userFunction( 'wp_cache_get' )
			->once()
			->with( 'xama_cache_questions_1' )
			->andReturn( [] );

		\WP_Mock::userFunction( 'wp_cache_set' )
			->once()
			->with( 'xama_cache_questions_1', [] )
			->andReturn( null );

		\WP_Mock::userFunction( 'get_posts' )
			->once()
			->andReturn( [] );

		\WP_Mock::userFunction( 'get_user_by' )
			->once()
			->with( 'id', 1 )
			->andReturn( $user );

		\WP_Mock::userFunction(
			'esc_html__',
			[
				'times'  => 4,
				'return' => function ( $text, $domain = Settings::DOMAIN ) {
					return $text;
				},
			]
		);

		\WP_Mock::userFunction(
			'esc_html',
			[
				'times'  => 4,
				'return' => function ( $text ) {
					return $text;
				},
			]
		);

		$reflection = new \ReflectionClass( $this->metabox );
		$method     = $reflection->getMethod( 'get_scores_heading_labels_and_data' );
		$method->setAccessible( true );

		$headings = $method->invoke( $this->metabox );

		$this->assertConditionsMet();
	}

	public function test_get_question_and_score_info() {
		$this->metabox->id = 1;

		// The asnwer the user provided...
		$this->metabox->scores_meta['xama_score_answer_1'][0] = 1;

		\WP_Mock::userFunction( 'get_the_title' )
			->once()
			->with( 1 )
			->andReturn( 'What is a Butterfly?' );

		\WP_Mock::userFunction( 'esc_html' )
			->once()
			->with( 'What is a Butterfly?' )
			->andReturn( 'What is a Butterfly?' );

		\WP_Mock::userFunction( 'esc_attr' )
			->once()
			->with( 'rebeccapurple' )
			->andReturn( 'rebeccapurple' );

		\WP_Mock::userFunction( 'esc_attr' )
			->once()
			->with( 'dashicons dashicons-yes' )
			->andReturn( 'dashicons dashicons-yes' );

		\WP_Mock::userFunction( 'get_post_meta' )
			->once()
			->with( 1, 'xama_answer', true )
			->andReturn( 1 );

		\WP_Mock::userFunction( 'esc_html__' )
			->twice()
			->with( 'A', 'xama' )
			->andReturn( 'A' );

		\WP_Mock::userFunction( 'esc_html__' )
			->once()
			->with( 'Question\'s Answer', 'xama' )
			->andReturn( 'Question\'s Answer' );

		\WP_Mock::userFunction( 'esc_html__' )
			->once()
			->with( 'User\'s Answer', 'xama' )
			->andReturn( 'User\'s Answer' );

		$reflection = new \ReflectionClass( $this->metabox );
		$method     = $reflection->getMethod( 'get_question_and_score_info' );
		$method->setAccessible( true );

		$key      = 1;
		$value[0] = true;

		$headings = $method->invoke( $this->metabox, $key, $value );

		$this->assertConditionsMet();
	}
}
