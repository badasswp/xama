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
