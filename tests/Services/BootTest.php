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
		\WP_Mock::expectActionAdded( 'init', [ $this->boot, 'register_plugin_pages' ] );
		\WP_Mock::expectActionAdded( 'wp_loaded', [ $this->boot, 'unregister_admin_bar' ] );

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
					'read'                        => true,
					'edit_others_xama_score'      => true,
					'publish_xama_score'          => true,
					'read_xama_score'             => true,
					'edit_xama_score'             => true,
					'edit_published_xama_score'   => true,
					'delete_xama_score'           => true,
					'delete_published_xama_score' => true,
					'create_xama_score'           => true,
				]
			)
			->andReturn( null );

		$this->boot->register_plugin_role();

		$this->assertConditionsMet();
	}
}
