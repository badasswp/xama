<?php
/**
 * Container Class.
 *
 * This class is responsible for managing and registering
 * various services used by the Xama plugin. Services are
 * registered to bind custom logic to WordPress hooks.
 *
 * @package Xama
 */

namespace Xama\Core;

use Xama\Services\Auth;
use Xama\Services\Boot;
use Xama\Services\Menu;
use Xama\Services\REST;
use Xama\Services\Post;
use Xama\Services\Editor;
use Xama\Services\Assets;
use Xama\Services\MetaBox;
use Xama\Services\Notices;
use Xama\Services\Template;
use Xama\Services\Controller;

use Xama\Interfaces\Registrable;

/**
 * Container class.
 */
final class Container implements Registrable {
	/**
	 * Instantiated services.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public static array $services = [];

	/**
	 * Initialize services in the constructor.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		self::$services = [
			Auth::class,
			Boot::class,
			Menu::class,
			Post::class,
			MetaBox::class,
			REST::class,
			Template::class,
			Assets::class,
			Controller::class,
			Editor::class,
			Notices::class,
		];
	}

	/**
	 * Register services (bind logic to WP).
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register(): void {
		foreach ( Container::$services as $service ) {
			( $service::get_instance() )->register();
		}
	}
}
