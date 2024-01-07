<?php

namespace Xama\Tests\Helpers;

use stdClass;
use Xama\Posts\Quiz;
use WP_Mock\Tools\TestCase;

require_once __DIR__ . '/../../inc/Helpers/functions.php';

/**
 * @covers Functions
 */
class FunctionsTest extends TestCase {
	public function setUp(): void {
		\WP_Mock::setUp();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_xama_get_quizzies() {
		$post = new stdClass();

		$post->ID           = 1;
		$post->post_title   = 'Hello World';
		$post->post_content = '';

		$posts = [
			[
				$post->ID,
				$post->post_title,
				$post->post_content,
			],
		];

		\WP_Mock::userFunction( 'wp_cache_get' )
			->once()
			->with( 'xama_cache_quizzies' )
			->andReturn( $posts );

		$quizzies = xama_get_quizzies();

		$this->assertIsArray( $quizzies );
		$this->assertConditionsMet();
	}

	public function test_xama_get_quizzies_sets_cache_if_not_set() {
		$post = new stdClass();

		$post->ID           = 1;
		$post->post_title   = 'Hello World';
		$post->post_content = '';

		$posts = [
			[
				$post->ID,
				$post->post_title,
				$post->post_content,
			],
		];

		\WP_Mock::userFunction( 'wp_cache_get' )
			->once()
			->with( 'xama_cache_quizzies' )
			->andReturn( '' );

		\WP_Mock::userFunction( 'get_posts' )
			->once()
			->andReturn( $posts );

		\WP_Mock::userFunction( 'wp_cache_set' )
			->once()
			->with( 'xama_cache_quizzies', $posts )
			->andReturn( null );

		$quizzies = xama_get_quizzies();

		$this->assertIsArray( $quizzies );
		$this->assertSame( $quizzies, $posts );
		$this->assertConditionsMet();
	}

	public function test_xama_get_questions_if_id_is_null() {
		$questions = xama_get_questions( null );

		$this->assertIsArray( $questions );
		$this->assertSame( $questions, [] );
		$this->assertConditionsMet();
	}

	public function test_xama_get_questions() {
		$post = new stdClass();

		$post->ID           = 1;
		$post->post_title   = 'Hello World';
		$post->post_content = '';

		$posts = [
			[
				$post->ID,
				$post->post_title,
				$post->post_content,
			],
		];

		\WP_Mock::userFunction( 'wp_cache_get' )
			->once()
			->with( 'xama_cache_questions_1' )
			->andReturn( $posts );

		$questions = xama_get_questions( 1 );

		$this->assertIsArray( $questions );
		$this->assertSame( $questions, $posts );
		$this->assertConditionsMet();
	}
}
