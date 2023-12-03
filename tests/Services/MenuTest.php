<?php

namespace Xama\Tests\Services;

use stdClass;
use Xama\Core\Settings;
use Xama\Services\Menu;
use WP_Mock\Tools\TestCase;

/**
 * @covers Menu
 */
class MenuTest extends TestCase {
	private $menu;

	public function setUp(): void {
		\WP_Mock::setUp();

		$this->menu = new Menu();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_register() {
		\WP_Mock::expectActionAdded( 'admin_menu', [ $this->menu, 'register_menu' ] );

		$this->menu->register();

		$this->assertConditionsMet();
	}

	public function test_register_menu() {
		\WP_Mock::userFunction( '__' )
			->twice()
			->with( 'Xama', 'xama' )
			->andReturn( 'Xama' );

		\WP_Mock::userFunction( 'add_menu_page' )
			->once()
			->with(
				'Xama',
				'Xama',
				'manage_options',
				'xama',
				false,
				'dashicons-welcome-learn-more',
				99
			)
			->andReturn( null );

		$this->menu->register_menu();

		$this->assertConditionsMet();
	}
}
