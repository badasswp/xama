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
		$post_name = $this->metabox->get_name();

		$this->assertSame( $post_name, 'xama_mb_metabox' );
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
