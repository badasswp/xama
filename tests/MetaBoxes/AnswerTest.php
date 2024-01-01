<?php

namespace Xama\Tests\MetaBoxes;

use stdClass;
use Xama\Core\Settings;
use Xama\MetaBoxes\Answer;
use WP_Mock\Tools\TestCase;

/**
 * @covers Answer
 */
class AnswerTest extends TestCase {
	private $metabox;

	public function setUp(): void {
		\WP_Mock::setUp();

		$this->metabox = new Answer();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_get_name() {
		$name = Answer::$name;

		$this->assertSame( $name, 'xama_mb_answer' );
		$this->assertConditionsMet();
	}

	public function test_get_heading() {
		$heading = $this->metabox->get_heading();

		$this->assertSame( $heading, 'Question Answer' );
		$this->assertConditionsMet();
	}

	public function test_get_post_type() {
		$post_type = $this->metabox->get_post_type();

		$this->assertSame( $post_type, 'xama_question' );
		$this->assertConditionsMet();
	}

	public function test_get_position() {
		$position = $this->metabox->get_position();

		$this->assertSame( $position, '' );
		$this->assertConditionsMet();
	}

	public function test_get_priority() {
		$priority = $this->metabox->get_priority();

		$this->assertSame( $priority, '' );
		$this->assertConditionsMet();
	}

	public function test_get_metabox_callback() {
		\WP_Mock::userFunction( 'get_post_meta' )
			->once()
			->with( 1, 'xama_answer', true )
			->andReturn( 1 );

		\WP_Mock::userFunction( 'get_post_meta' )
			->once()
			->with( 1, 'xama_options', true )
			->andReturn( array_fill( 0, 4, '' ) );

		\WP_Mock::userFunction(
			'esc_attr',
			[
				'times'  => 8,
				'return' => function ( $i ) {
					return $i;
				},
			]
		);

		\WP_Mock::userFunction(
			'esc_html__',
			[
				'times'  => 4,
				'return' => function ( $text, $domain = Settings::DOMAIN ) {
					return $text;
				},
			]
		);

		\WP_Mock::userFunction( 'selected' )
			->once()
			->with( 1, 1, false )
			->andReturn( 'selected' );

		\WP_Mock::userFunction( 'selected' )
			->times( 3 );

		$post     = new stdClass();
		$post->ID = 1;

		$this->metabox->options = '';

		$this->metabox->get_metabox_callback( $post );

		$this->expectOutputString(
			'<select
				class="widefat"
				name="xama_answer"
				style="margin-top: 5px;"
			><option value="0" >
					A
				</option><option value="1" selected>
					B
				</option><option value="2" >
					C
				</option><option value="3" >
					D
				</option></select>'
		);

		$this->assertConditionsMet();
	}

	public function test_save_meta_box() {
		$_POST['xama_answer'] = '<script type="src/javascript">console.log(1)</script>1';

		$post     = new stdClass();
		$post->ID = 1;

		\WP_Mock::userFunction( 'sanitize_text_field' )
			->once()
			->with( '<script type="src/javascript">console.log(1)</script>1' )
			->andReturn( 1 );

		\WP_Mock::userFunction( 'update_post_meta' )
			->once()
			->with( 1, 'xama_answer', 1 )
			->andReturn( null );

		$this->metabox->save_meta_box( $post->ID, $post );

		$this->assertConditionsMet();
	}
}
