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
			->once();

		$this->template = new Template();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_register() {
		\WP_Mock::expectFilterAdded( 'template_include', [ $this->template, 'register_template' ] );

		$this->template->register();

		$this->assertConditionsMet();
	}
}
