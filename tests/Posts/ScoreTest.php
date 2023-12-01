<?php

namespace Xama\Tests\Posts;

use stdClass;
use Xama\Core\Settings;
use Xama\Posts\Score;
use WP_Mock\Tools\TestCase;

/**
 * @covers Score
 */
class ScoreTest extends TestCase {
	private $post;

	public function setUp(): void {
		\WP_Mock::setUp();

		$this->post = new Score();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_get_name() {
		$name = Score::$name;

		$this->assertSame( $name, 'xama_score' );
		$this->assertConditionsMet();
	}

	public function test_get_singular_label() {
		$singular_label = $this->post->get_singular_label();

		$this->assertSame( $singular_label, 'Score' );
		$this->assertConditionsMet();
	}

	public function test_get_plural_label() {
		$plural_label = $this->post->get_plural_label();

		$this->assertSame( $plural_label, 'Scores' );
		$this->assertConditionsMet();
	}

	public function test_get_supports() {
		\WP_Mock::onFilter( 'xama_score_supports' )
			->with( [ 'title' ] )
			->reply( [ 'title', 'thumbnail' ] );

		$supports = $this->post->get_supports();

		$this->assertSame( $supports, [ 'title', 'thumbnail' ] );
		$this->assertConditionsMet();
	}

	public function test_register_post_column_data() {
		$column = 'percentage';
		$post   = new stdClass();

		$post->ID         = 1;
		$post->post_title = 'What a Wonderful World?';

		\WP_Mock::userFunction( 'get_post_meta' )
			->once()
			->with( 100, 'xama_score_quiz_id', true )
			->andReturn( 1 );

		\WP_Mock::userFunction( 'get_the_title' )
			->with( 1 )
			->andReturn( 'johndoe | WordPress Quiz' );

		\WP_Mock::userFunction( 'get_post_meta' )
			->once()
			->with( 100, 'xama_score_total', true )
			->andReturn( 2 );

		\WP_Mock::userFunction( 'wp_cache_get' )
			->once()
			->with( 'xama_cache_questions_1' )
			->andReturn( [ $post, $post, $post, $post ] );

		\WP_Mock::userFunction( 'esc_html' )
			->once()
			->with( 50 )
			->andReturn( 50 );

		\WP_Mock::expectAction( 'xama_score_column_data', 'percentage', 100 );

		$this->post->register_post_column_data( $column, 100 );

		$this->expectOutputString( 50 );
		$this->assertConditionsMet();
	}

	public function test_register_post_columns() {
		$columns = [
			'quiz'       => 'Quiz',
			'score'      => 'Score',
			'total'      => 'Total',
			'percentage' => 'Percentage (%)',
			'date'       => 'Date',
		];

		\WP_Mock::userFunction(
			'esc_html__',
			[
				'return' => function ( $column, $domain = Settings::DOMAIN ) {
					return $column;
				},
			]
		);

		\WP_Mock::expectFilter( 'xama_score_columns', $columns );

		$columns = $this->post->register_post_columns( $columns );

		$this->assertSame(
			$columns,
			[
				'quiz'       => 'Quiz',
				'score'      => 'Score',
				'total'      => 'Total',
				'percentage' => 'Percentage (%)',
				'date'       => 'Date',
			]
		);
		$this->assertConditionsMet();
	}

	public function test_url_slug() {
		$slug = $this->post->url_slug();

		$this->assertSame( $slug, 'score' );
		$this->assertConditionsMet();
	}

	public function test_is_post_visible_in_rest() {
		\WP_Mock::expectFilter( 'xama_score_visible_in_rest', false );

		$this->post->is_post_visible_in_rest();

		$this->assertConditionsMet();
	}
}
