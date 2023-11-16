<?php

namespace Xama\Tests\MetaBoxes;

use Mockery;
use stdClass;
use Xama\Core\Settings;
use WP_Mock\Tools\TestCase;
use Xama\MetaBoxes\Scores;

/**
 * @covers Scores
 */
class ScoresTest extends TestCase {
	private $scores;

	public function setUp(): void {
		\WP_Mock::setUp();

		$this->scores = new Scores();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}
}
