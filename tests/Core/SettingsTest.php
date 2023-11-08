<?php

namespace Xama\Tests\Core;

use Xama\Core\Settings;
use WP_Mock\Tools\TestCase;

/**
 * @covers Settings
 */
class SettingsTest extends TestCase {
	public function setUp(): void {
		\WP_Mock::setUp();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_all_settings_property_match() {
		$this->assertSame( Settings::NAME, 'Xama' );
		$this->assertSame( Settings::SLUG, 'xama' );
		$this->assertSame( Settings::DOMAIN, 'xama' );
		$this->assertSame( Settings::ROLE, 'manage_options' );
		$this->assertSame( Settings::BOOL, [ 'No', 'Yes' ] );
		$this->assertSame(
			Settings::OPTIONS,
			[
				1 => 'A',
				2 => 'B',
				3 => 'C',
				4 => 'D',
			]
		);
		$this->assertConditionsMet();
	}
}
