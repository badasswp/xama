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

		$this->scores = new Scores();
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
		$heading = $this->scores->get_heading();

		$this->assertSame( $heading, 'Quiz Scores' );
		$this->assertConditionsMet();
	}

	public function test_get_post_type() {
		$post_type = $this->scores->get_post_type();

		$this->assertSame( $post_type, 'xama_score' );
		$this->assertConditionsMet();
	}

	public function test_get_position() {
		$position = $this->scores->get_position();

		$this->assertSame( $position, '' );
		$this->assertConditionsMet();
	}

	public function test_get_priority() {
		$priority = $this->scores->get_priority();

		$this->assertSame( $priority, 'high' );
		$this->assertConditionsMet();
	}

	public function test_get_question_options() {
		$this->scores->id = 1;

		\WP_Mock::userFunction( 'get_post_meta' )
			->once()
			->with( 1, 'xama_option_1', true )
			->andReturn( 'Option 1' );

		\WP_Mock::userFunction( 'get_post_meta' )
			->once()
			->with( 1, 'xama_option_2', true )
			->andReturn( 'Option 2' );

		\WP_Mock::userFunction( 'get_post_meta' )
			->once()
			->with( 1, 'xama_option_3', true )
			->andReturn( 'Option 3' );

		\WP_Mock::userFunction( 'get_post_meta' )
			->once()
			->with( 1, 'xama_option_4', true )
			->andReturn( 'Option 4' );

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

		$reflection = new \ReflectionClass( $this->scores );
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

		$options = $method->invoke( $this->scores );

		$this->assertSame( $expected, $options );
		$this->assertConditionsMet();
	}

	public function test_get_scores_heading_labels_and_data() {
		$this->scores->scores_meta['xama_score_user_name'][0] = 'John Doe';
		$this->scores->scores_meta['xama_score_user_email'][0] = 'john@doe.com';
		$this->scores->scores_meta['xama_score_total'][0] = '100';
		$this->scores->scores_meta['xama_score_total_questions'][0] = '100';

		\WP_Mock::userFunction( 'esc_html__' )
			->once()
			->with( 'User Name', Settings::DOMAIN )
			->andReturn( 'User Name' );

		\WP_Mock::userFunction( 'esc_html__' )
			->once()
			->with( 'User Email', Settings::DOMAIN )
			->andReturn( 'User Email' );

		\WP_Mock::userFunction( 'esc_html__' )
			->once()
			->with( 'User Score', Settings::DOMAIN )
			->andReturn( 'User Score' );

		\WP_Mock::userFunction( 'esc_html__' )
			->once()
			->with( 'Total No. of Questions', Settings::DOMAIN )
			->andReturn( 'Total No. of Questions' );

		\WP_Mock::userFunction( 'esc_html' )
			->once()
			->with( 'John Doe' )
			->andReturn( 'John Doe' );

		\WP_Mock::userFunction( 'esc_html' )
			->once()
			->with( 'john@doe.com' )
			->andReturn( 'john@doe.com' );

		\WP_Mock::userFunction( 'esc_html' )
			->twice()
			->with( '100' )
			->andReturn( '100' );

		$reflection = new \ReflectionClass( $this->scores );
		$method = $reflection->getMethod( 'get_scores_heading_labels_and_data' );
		$method->setAccessible( true );

		$headings = $method->invoke( $this->scores );

		$this->assertConditionsMet();
	}
}
