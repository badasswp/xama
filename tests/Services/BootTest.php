<?php

namespace Xama\Tests\Services;

use stdClass;
use Xama\Core\Settings;
use Xama\Services\Boot;
use WP_Mock\Tools\TestCase;

/**
 * @covers Boot
 */
class BootTest extends TestCase {
	private $boot;

	public function setUp(): void {
		\WP_Mock::setUp();

		\WP_Mock::userFunction( 'plugin_basename' )
			->once();

		$this->boot = new Boot();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_register() {
		\WP_Mock::expectActionAdded( 'init', [ $this->boot, 'register_plugin_domain' ] );
		\WP_Mock::expectActionAdded( 'init', [ $this->boot, 'register_plugin_role' ] );

		$this->boot->register();

		$this->assertConditionsMet();
	}

	public function test_register_plugin_domain() {
		\WP_Mock::userFunction( 'load_plugin_textdomain' )
			->once()
			->with( 'xama', false, '/../../languages' );

		$this->boot->register_plugin_domain();

		$this->assertConditionsMet();
	}

	public function test_register_plugin_role() {
		\WP_Mock::userFunction( 'get_role' )
			->once()
			->with( 'xama' )
			->andReturn( null );

		\WP_Mock::userFunction( 'add_role' )
			->once()
			->with(
				'xama',
				'Xama',
				[
					'read'               => true,
					'edit_posts'         => true,
					'upload_files'       => true,
					'manage_categories'  => false,
					'edit_others_posts'  => false,
					'delete_posts'       => false,
					'edit_theme_options' => false,
					'install_plugins'    => false,
					'edit_users'         => false,
					'edit_plugins'       => false,
				]
			)
			->andReturn( null );

		$this->boot->register_plugin_role();

		$this->assertConditionsMet();
	}
}
