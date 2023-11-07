<?php

namespace Xama\Tests\Interfaces;

use Xama\Interfaces\Registrable;
use WP_Mock\Tools\TestCase;

/**
 * @covers Registrable
 */
class RegistrableTest extends TestCase {
	private $registrable;

	public function setUp(): void {
		\WP_Mock::setUp();

		$this->registrable = $this->getMockForAbstractClass( Registrable::class );
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_register() {
		$this->registrable->expects( $this->once() )
			->method( 'register' );

		$this->registrable->register();

		$this->assertConditionsMet();
	}
}
