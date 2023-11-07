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
		$this->post = new ConcretePost();
	}

	public function test_get_name() {
		$post_name = $this->post->get_name();

		$this->assertSame( $post_name, 'xama_quiz' );
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
