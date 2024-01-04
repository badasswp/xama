<?php

namespace Xama\Tests\Abstracts;

use stdClass;
use Xama\Core\Settings;
use Xama\Abstracts\Controller;
use WP_Mock\Tools\TestCase;

/**
 * @covers Controller
 */
class ControllerTest extends TestCase {
	private $metabox;

	public function setUp(): void {
		\WP_Mock::setUp();
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
	}

	public function test_get_instance() {
		// Server Req. Method
		$_SERVER['REQUEST_METHOD'] = 'POST';

		// Post Nonce
		$_POST['xama_nonce'] = 'xama_nonce';

		\WP_Mock::userFunction( 'wp_verify_nonce' )
			->once()
			->with( 'xama_nonce', 'xama_action' )
			->andReturn( true );

		$this->controller = new ConcreteController();

		$this->assertSame( $this->controller->data, $_POST );
		$this->assertConditionsMet();
	}
}

class ConcreteController extends Controller {
	public function run(): void {}
}
