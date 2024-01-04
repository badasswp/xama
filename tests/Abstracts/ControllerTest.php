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

	public function test_validate_for_empty_rules() {
		// Server Req. Method
		$_SERVER['REQUEST_METHOD'] = 'POST';

		// Post Nonce
		$_POST = [
			'xama_nonce'    => 'xama_nonce',
			'xama_username' => 'john@doe.com',
			'xama_password' => 'password',
		];

		\WP_Mock::userFunction( 'wp_verify_nonce' )
			->once()
			->with( 'xama_nonce', 'xama_action' )
			->andReturn( true );

		$this->controller = new ConcreteController();

		$this->controller->rules = [];

		$error_messages = $this->controller->validate();

		$this->assertSame( $error_messages, [] );
		$this->assertConditionsMet();
	}

	public function test_validate_for_filled_rules() {
		// Server Req. Method
		$_SERVER['REQUEST_METHOD'] = 'POST';

		// Post Nonce
		$_POST = [
			'xama_nonce'    => 'xama_nonce',
			'xama_username' => 'john',
			'xama_password' => 'jb',
		];

		\WP_Mock::userFunction( 'wp_verify_nonce' )
			->once()
			->with( 'xama_nonce', 'xama_action' )
			->andReturn( true );

		\WP_Mock::userFunction( 'is_email' )
			->once()
			->with( 'john' )
			->andReturn( false );

		$this->controller = new ConcreteController();

		$this->controller->rules = [
			'xama_username' => 'email',
			'xama_password' => 'password'
		];

		$error_messages = $this->controller->validate();

		$this->assertSame(
			$error_messages,
			[
				'Error: Validating Email Address, email must contain a valid email address...',
				'Error: Validating Password, password must contain a minimum of 6 unique characters...',
			]
		);
		$this->assertConditionsMet();
	}
}

class ConcreteController extends Controller {
	public function run(): void {}
}
