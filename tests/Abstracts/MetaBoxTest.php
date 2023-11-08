<?php

namespace Xama\Tests\Abstracts;

use stdClass;
use Xama\Core\Settings;
use Xama\Abstracts\MetaBox;
use WP_Mock\Tools\TestCase;

/**
 * @covers MetaBox
 */
class MetaBoxTest extends TestCase {
	private $metabox;

	public function setUp(): void {
		\WP_Mock::setUp();

		ConcreteMetaBox::$name = 'xama_mb_metabox';
		$this->metabox         = new ConcreteMetaBox();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_get_name() {
		$metabox_name = $this->metabox->get_name();

		$this->assertSame( $metabox_name, 'xama_mb_metabox' );
		$this->assertConditionsMet();
	}

	public function test_register_meta_box() {
		\WP_Mock::userFunction( 'esc_html__' )
			->once()
			->with( 'MetaBox Heading', 'xama' )
			->andReturn( 'MetaBox Heading' );

		\WP_Mock::expectFilter(
			'xama_metabox_options',
			[
				'name'      => $this->metabox->get_name(),
				'heading'   => $this->metabox->get_heading(),
				'post_type' => $this->metabox->get_post_type(),
				'position'  => $this->metabox->get_position(),
				'priority'  => $this->metabox->get_priority(),
			]
		);

		\WP_Mock::userFunction( 'add_meta_box' )
			->once()
			->with(
				'xama_mb_metabox',
				'MetaBox Heading',
				[ $this->metabox, 'get_metabox_callback' ],
				'xama_quiz',
				'advanced',
				'default'
			);

		$this->metabox->register_meta_box();

		$this->assertConditionsMet();
	}
}

class ConcreteMetaBox extends MetaBox {
	public function get_heading(): string {
		return 'MetaBox Heading';
	}

	public function get_post_type(): string {
		return 'xama_quiz';
	}

	public function get_position(): string {
		return 'advanced';
	}

	public function get_priority(): string {
		return 'default';
	}

	public function get_metabox_callback( $post ): void {}
	public function save_meta_box( $post_id, $post ): void {}
}
