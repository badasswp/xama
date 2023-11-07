<?php

namespace Xama\Tests\Abstracts;

use Xama\Core\Settings;
use Xama\Abstracts\Post;
use WP_Mock\Tools\TestCase;

/**
 * @covers Post
 */
class PostTest extends TestCase {
	private $post;

	public function setUp(): void {
		ConcretePost::$name = 'xama_quiz';
		$this->post         = new ConcretePost();
	}

	public function test_get_name() {
		$post_name = $this->post->get_name();

		$this->assertSame( $post_name, 'xama_quiz' );
		$this->assertConditionsMet();
	}

	public function test_register_post_type() {
		\WP_Mock::userFunction( 'post_type_exists' )
			->once()
			->andReturn( false );

		\WP_Mock::userFunction( 'register_post_type' )
			->once();

		\WP_Mock::userFunction( '__' )
			->times( 18 );

		\WP_Mock::expectFilter(
			'xama_post_options',
			[
				'labels'       => $this->post->get_labels(),
				'public'       => true,
				'has_archive'  => true,
				'show_in_menu' => Settings::DOMAIN,
				'supports'     => $this->post->get_supports(),
				'show_in_rest' => $this->post->is_post_visible_in_rest(),
				'rewrite'      => [
					'slug' => ( method_exists( $this->post, 'url_slug' ) ) ? $this->post->url_slug() : '',
				],
			]
		);

		$this->post->register_post_type();

		$this->assertConditionsMet();
	}

	public function test_get_labels() {
		\WP_Mock::userFunction( '__' )
			->times( 9 );

		$labels = $this->post->get_labels();

		$this->assertIsArray( $labels );
		$this->assertTrue( array_key_exists( 'name', $labels ) );
		$this->assertTrue( array_key_exists( 'singular_name', $labels ) );
		$this->assertTrue( array_key_exists( 'add_new', $labels ) );
		$this->assertTrue( array_key_exists( 'add_new_item', $labels ) );
		$this->assertTrue( array_key_exists( 'new_item', $labels ) );
		$this->assertTrue( array_key_exists( 'edit_item', $labels ) );
		$this->assertTrue( array_key_exists( 'view_item', $labels ) );
		$this->assertTrue( array_key_exists( 'search_items', $labels ) );
		$this->assertTrue( array_key_exists( 'menu_name', $labels ) );
		$this->assertConditionsMet();
	}

	public function test_get_permalink() {
		\WP_Mock::userFunction( 'esc_attr' )
			->once();

		\WP_Mock::userFunction( 'esc_html' )
			->once();

		\WP_Mock::userFunction( 'get_permalink' )
			->twice()
			->andReturn( 'http://example.com/quiz' );

		$link = $this->post->get_permalink( 1 );

		$this->assertTrue( (bool) preg_match( '/target="_blank"/', $link ) );
		$this->assertConditionsMet();
	}
}

class ConcretePost extends Post {
	public function get_singular_label(): string {
		return 'Quiz';
	}

	public function get_plural_label(): string {
		return 'Quizzes';
	}

	public function get_supports(): array {
		return [ 'title', 'editor' ];
	}

	public function is_post_visible_in_rest(): bool {
		return false;
	}

	public function url_slug(): string {
		return 'quiz';
	}

	public function get_labels(): array {
		$singular_label = $this->get_singular_label();
		$plural_label   = $this->get_plural_label();

		$labels = [
			'name'          => sprintf(
				'%1$s',
				__( $plural_label, Settings::DOMAIN ),
			),
			'singular_name' => sprintf(
				'%1$s',
				__( $singular_label, Settings::DOMAIN ),
			),
			'add_new'       => sprintf(
				'%1$s',
				__( "Add New {$singular_label}", Settings::DOMAIN ),
			),
			'add_new_item'  => sprintf(
				'%1$s',
				__( "Add New {$singular_label}", Settings::DOMAIN ),
			),
			'new_item'      => sprintf(
				'%1$s',
				__( "New {$singular_label}", Settings::DOMAIN ),
			),
			'edit_item'     => sprintf(
				'%1$s',
				__( "Edit {$singular_label}", Settings::DOMAIN ),
			),
			'view_item'     => sprintf(
				'%1$s',
				__( "View {$singular_label}", Settings::DOMAIN ),
			),
			'search_items'  => sprintf(
				'%1$s',
				__( "Search {$plural_label}", Settings::DOMAIN ),
			),
			'menu_name'     => sprintf(
				'%1$s',
				__( $plural_label, Settings::DOMAIN ),
			),
		];

		return $labels;
	}

	public function save_post_type( $post_id, $post ): void {}
	public function register_post_columns( $columns ): array {}
	public function register_post_column_data( $column, $post_id ): void {}
}
