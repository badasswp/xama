<?php

namespace Xama\Tests\Services;

use stdClass;
use Xama\Core\Settings;
use Xama\Services\Template;
use WP_Mock\Tools\TestCase;

/**
 * @covers Template
 */
class TemplateTest extends TestCase {
	private $template;

	public function setUp(): void {
		\WP_Mock::setUp();

		\WP_Mock::userFunction( 'plugin_dir_path' )
			->once()
			->andReturn( './inc/Services/' );

		$this->template = new Template();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_register() {
		\WP_Mock::expectFilterAdded( 'template_include', [ $this->template, 'register_template' ] );
		\WP_Mock::expectFilterAdded( 'template_include', [ $this->template, 'register_page_templates' ] );

		$this->template->register();

		$this->assertConditionsMet();
	}

	public function test_register_wp_template() {
		$wp_template = '';

		\WP_Mock::userFunction( 'get_post_type' )
			->once()
			->andReturn( 'post' );

		$template = $this->template->register_template( $wp_template );

		$this->assertSame( $template, '' );
		$this->assertConditionsMet();
	}

	public function test_register_xama_template() {
		$wp_template = '';

		\WP_Mock::userFunction( 'get_post_type' )
			->once()
			->with()
			->andReturn( 'xama_quiz' );

		$template = $this->template->register_template( $wp_template );

		$this->assertSame( $template, $this->template->template['index'] );
		$this->assertConditionsMet();
	}
}
