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

	public function test_register_meta_boxes() {
		\WP_Mock::userFunction(
			'esc_html__',
			[
				'times'  => 4,
				'return' => function ( $text, $domain = Settings::DOMAIN ) {
					return $text;
				},
			]
		);

		// Loop 1
		\WP_Mock::expectFilter(
			'xama_metabox_options',
			[
				'name'      => 'xama_mb_answer',
				'heading'   => 'Question Answer',
				'post_type' => 'xama_question',
				'position'  => '',
				'priority'  => '',
			]
		);

		\WP_Mock::userFunction( 'add_meta_box' )
			->once()
			->with(
				'xama_mb_answer',
				'Question Answer',
				[ new Answer(), 'get_metabox_callback' ],
				'xama_question',
				'advanced',
				'default'
			);

		// Loop 2
		\WP_Mock::expectFilter(
			'xama_metabox_options',
			[
				'name'      => 'xama_mb_options',
				'heading'   => 'Question Options',
				'post_type' => 'xama_question',
				'position'  => '',
				'priority'  => 'high',
			]
		);

		\WP_Mock::userFunction( 'add_meta_box' )
			->once()
			->with(
				'xama_mb_options',
				'Question Options',
				[ new Options(), 'get_metabox_callback' ],
				'xama_question',
				'advanced',
				'high'
			);

		// Loop 3
		\WP_Mock::expectFilter(
			'xama_metabox_options',
			[
				'name'      => 'xama_mb_questions',
				'heading'   => 'Quiz Questions',
				'post_type' => 'xama_quiz',
				'position'  => '',
				'priority'  => 'high',
			]
		);

		\WP_Mock::userFunction( 'add_meta_box' )
			->once()
			->with(
				'xama_mb_questions',
				'Quiz Questions',
				[ new Questions(), 'get_metabox_callback' ],
				'xama_quiz',
				'advanced',
				'high'
			);

		// Loop 4
		\WP_Mock::expectFilter(
			'xama_metabox_options',
			[
				'name'      => 'xama_mb_scores',
				'heading'   => 'Quiz Scores',
				'post_type' => 'xama_score',
				'position'  => '',
				'priority'  => 'high',
			]
		);

		\WP_Mock::userFunction( 'add_meta_box' )
			->once()
			->with(
				'xama_mb_scores',
				'Quiz Scores',
				[ new Scores(), 'get_metabox_callback' ],
				'xama_score',
				'advanced',
				'high'
			);

		$this->metabox->register_meta_boxes();

		$this->assertConditionsMet();
	}
}
