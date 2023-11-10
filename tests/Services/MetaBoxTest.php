<?php

namespace Xama\Tests\Services;

use stdClass;
use Xama\Core\Settings;
use Xama\Services\MetaBox;
use WP_Mock\Tools\TestCase;

use Xama\MetaBoxes\Answer;
use Xama\MetaBoxes\Options;
use Xama\MetaBoxes\Scores;
use Xama\MetaBoxes\Questions;

/**
 * @covers MetaBox
 */
class MetaBoxTest extends TestCase {
	private $metabox;

	public function setUp(): void {
		\WP_Mock::setUp();

		\WP_Mock::expectFilter(
			'xama_meta_boxes',
			[
				Answer::class,
				Options::class,
				Questions::class,
				Scores::class,
			]
		);

		$this->metabox = new MetaBox();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_register() {
		\WP_Mock::expectActionAdded( 'add_meta_boxes', [ $this->metabox, 'register_meta_boxes' ] );

		\WP_Mock::expectActionAdded( 'publish_xama_question', [ $this->metabox->objects[0], 'save_meta_box' ], 10, 2 );
		\WP_Mock::expectActionAdded( 'publish_xama_question', [ $this->metabox->objects[1], 'save_meta_box' ], 10, 2 );
		\WP_Mock::expectActionAdded( 'publish_xama_quiz', [ $this->metabox->objects[2], 'save_meta_box' ], 10, 2 );
		\WP_Mock::expectActionAdded( 'publish_xama_score', [ $this->metabox->objects[3], 'save_meta_box' ], 10, 2 );

		$this->metabox->register();

		$this->assertConditionsMet();
	}
}
