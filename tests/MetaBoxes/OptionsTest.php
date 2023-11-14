<?php

namespace Xama\Tests\MetaBoxes;

use Mockery;
use stdClass;
use Xama\Core\Settings;
use WP_Mock\Tools\TestCase;
use Xama\MetaBoxes\Options;

/**
 * @covers Options
 */
class OptionsTest extends TestCase {
	private $options;

	public function setUp(): void {
		\WP_Mock::setUp();

		$this->options = new Options();

		$post     = new stdClass();
		$post->ID = 1;

		$this->options->post = $post;
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_get_name() {
		$name = Options::$name;

		$this->assertSame( $name, 'xama_mb_options' );
		$this->assertConditionsMet();
	}

	public function test_get_heading() {
		$heading = $this->options->get_heading();

		$this->assertSame( $heading, 'Question Options' );
		$this->assertConditionsMet();
	}

	public function test_get_post_type() {
		$post_type = $this->options->get_post_type();

		$this->assertSame( $post_type, 'xama_question' );
		$this->assertConditionsMet();
	}

	public function test_get_position() {
		$position = $this->options->get_position();

		$this->assertSame( $position, '' );
		$this->assertConditionsMet();
	}

	public function test_get_priority() {
		$priority = $this->options->get_priority();

		$this->assertSame( $priority, 'high' );
		$this->assertConditionsMet();
	}

	public function test_get_button() {
		$url = 'http://example.com/wp-admin/post.php?post=1&action=edit';

		\WP_Mock::userFunction( 'esc_url' )
			->once()
			->with( $url )
			->andReturn( $url );

		\WP_Mock::userFunction( 'esc_html__' )
			->once()
			->with( 'Go Back To Quiz', Settings::DOMAIN )
			->andReturn( 'Go Back To Quiz' );

		\WP_Mock::userFunction( 'get_post_meta' )
			->once()
			->with( 1, 'xama_quiz_id', true )
			->andReturn( 1 );

		\WP_Mock::userFunction( 'home_url' )
			->once()
			->andReturn( 'http://example.com' );

		\WP_Mock::userFunction( 'absint' )
			->once()
			->with( 1 )
			->andReturn( 1 );

		$reflection = new \ReflectionClass( $this->options );
		$method     = $reflection->getMethod( 'get_button' );
		$method->setAccessible( true );

		$button = $method->invoke( $this->options );

		$expected = '<div>
				<a
					href="http://example.com/wp-admin/post.php?post=1&action=edit"
					class="button button-primary button-large"
					style="margin-top: 5px;"
				>Go Back To Quiz</a>
			</div>';

		$this->assertStringContainsString( '/wp-admin/post.php?post=1', $expected );
		$this->assertSame( $expected, $button );
		$this->assertConditionsMet();
	}

	public function test_get_button_url() {
		\WP_Mock::userFunction( 'get_post_meta' )
			->once()
			->with( 1, 'xama_quiz_id', true )
			->andReturn( 1 );

		\WP_Mock::userFunction( 'home_url' )
			->once()
			->andReturn( 'http://example.com' );

		\WP_Mock::userFunction( 'absint' )
			->once()
			->with( 1 )
			->andReturn( 1 );

		$reflection = new \ReflectionClass( $this->options );
		$method     = $reflection->getMethod( 'get_button_url' );
		$method->setAccessible( true );

		$url = $method->invoke( $this->options );

		$expected = 'http://example.com/wp-admin/post.php?post=1&action=edit';

		$this->assertStringContainsString( '/wp-admin/post.php?post=1', $expected );
		$this->assertSame( $expected, $url );
		$this->assertConditionsMet();
	}

	public function test_get_options() {
		\WP_Mock::userFunction( 'get_post_meta' )
			->times( 4 )
			->andReturnValues(
				[
					'Option 1',
					'Option 2',
					'Option 3',
					'Option 4',
				]
			);

		\WP_Mock::userFunction( 'esc_attr' )
			->times( 8 )
			->andReturnValues(
				[
					'1',
					'Option Value 1',
					'2',
					'Option Value 2',
					'3',
					'Option Value 3',
					'4',
					'Option Value 4',
				]
			);

		\WP_Mock::userFunction( 'esc_html__' )
			->times( 4 )
			->andReturnValues(
				[
					'Option 1',
					'Option 2',
					'Option 3',
					'Option 4',
				]
			);

		$reflection = new \ReflectionClass( $this->options );
		$method     = $reflection->getMethod( 'get_options' );
		$method->setAccessible( true );

		$expected = '<p>
					<label for="option1">
						Option 1
					</label>
					<br/>
					<input
						type="text"
						class="widefat"
						name="xama_option_1"
						value="Option Value 1"
					/>
				</p><p>
					<label for="option2">
						Option 2
					</label>
					<br/>
					<input
						type="text"
						class="widefat"
						name="xama_option_2"
						value="Option Value 2"
					/>
				</p><p>
					<label for="option3">
						Option 3
					</label>
					<br/>
					<input
						type="text"
						class="widefat"
						name="xama_option_3"
						value="Option Value 3"
					/>
				</p><p>
					<label for="option4">
						Option 4
					</label>
					<br/>
					<input
						type="text"
						class="widefat"
						name="xama_option_4"
						value="Option Value 4"
					/>
				</p>';

		$options = $method->invoke( $this->options );

		$this->assertSame( $expected, $options );
		$this->assertConditionsMet();
	}

	public function test_save_meta_box() {
		\WP_Mock::userFunction( 'update_post_meta' )
			->times( 5 );

		\WP_Mock::userFunction( 'sanitize_text_field' )
			->times( 5 )
			->andReturn(
				[
					'Option 1',
					'Option 2',
					'Option 3',
					'Option 4',
					'1',
				]
			);

		$_POST = [
			'xama_option_1' => 'Option 1',
			'xama_option_2' => 'Option 2',
			'xama_option_3' => 'Option 3',
			'xama_option_4' => 'Option 4',
			'xama_quiz_id'  => '1',
		];

		$this->options->save_meta_box( $this->options->post->ID, $this->options->post );

		$this->assertConditionsMet();
	}
}
