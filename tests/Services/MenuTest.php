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
}
