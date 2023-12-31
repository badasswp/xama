<?php

namespace Xama\Tests\Core;

use Xama\Services\Auth;
use Xama\Services\Boot;
use Xama\Services\Menu;
use Xama\Services\Post;
use Xama\Services\REST;
use Xama\Services\Assets;
use Xama\Services\Editor;
use Xama\Services\MetaBox;
use Xama\Services\Notices;
use Xama\Services\Template;
use Xama\Services\Controller;

use Xama\Posts\Quiz;
use Xama\Posts\Score;
use Xama\Posts\Question;

use Xama\MetaBoxes\Answer;
use Xama\MetaBoxes\Scores;
use Xama\MetaBoxes\Options;
use Xama\MetaBoxes\Questions;

use WP_Mock\Tools\TestCase;
use Xama\Core\Container;
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

	public function test_stores_all_services() {
		$this->assertIsArray( Container::$services );
		$this->assertTrue( in_array( Auth::class, Container::$services, true ) );
		$this->assertTrue( in_array( Boot::class, Container::$services, true ) );
		$this->assertTrue( in_array( Menu::class, Container::$services, true ) );
		$this->assertTrue( in_array( Post::class, Container::$services, true ) );
		$this->assertTrue( in_array( MetaBox::class, Container::$services, true ) );
		$this->assertTrue( in_array( REST::class, Container::$services, true ) );
		$this->assertTrue( in_array( Template::class, Container::$services, true ) );
		$this->assertTrue( in_array( Assets::class, Container::$services, true ) );
		$this->assertTrue( in_array( Controller::class, Container::$services, true ) );
		$this->assertTrue( in_array( Editor::class, Container::$services, true ) );
		$this->assertTrue( in_array( Notices::class, Container::$services, true ) );
		$this->assertConditionsMet();
	}

	public function test_registers_all_services() {
		\WP_Mock::userFunction( 'plugin_basename' )
			->once();

		\WP_Mock::userFunction( 'plugin_dir_path' )
			->once();

		\WP_Mock::expectFilter(
			'xama_editor_post_types',
			[
				Quiz::$name     => 'Quiz',
				Question::$name => 'Question',
			]
		);

		\WP_Mock::expectFilter(
			'xama_meta_boxes',
			[
				Answer::class,
				Options::class,
				Questions::class,
				Scores::class,
			]
		);

		\WP_Mock::expectFilter(
			'xama_post_types',
			[
				Quiz::class,
				Question::class,
				Score::class,
			]
		);

		foreach ( Container::$services as $key => $service ) {
			$mocked_service = $this->getMockBuilder( $service )
				->disableOriginalConstructor()
				->onlyMethods( [ 'register' ] )
				->getMock();

			Container::$services[ $key ] = $mocked_service;
		}

		$this->container->register();

		$this->assertConditionsMet();
	}
}
