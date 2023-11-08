<?php

namespace Xama\Tests\Core;

use Xama\Services\Auth;
use Xama\Services\Boot;
use Xama\Services\Menu;
use Xama\Services\Post;
use Xama\Services\REST;
use Xama\Services\Metabox;
use Xama\Services\Template;

use Xama\Core\Container;
use WP_Mock\Tools\TestCase;

/**
 * @covers Container
 */
class ContainerTest extends TestCase {
	private $container;

	public function setUp(): void {
		\WP_Mock::setUp();

		$this->container = new Container();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}
}
