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
				0 => 'A',
				1 => 'B',
				2 => 'C',
				3 => 'D',
				4 => 'E',
				5 => 'F',
				6 => 'G',
				7 => 'H',
			]
		);
		$this->assertConditionsMet();
	}
}
