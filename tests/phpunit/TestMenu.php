<?php

namespace Jay\AwesomePostView\Tests;

use Jay\AwesomePostView\Admin\Menu;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

/**
 * Admin menu test case.
 *
 * @since 1.0.0
 *
 * @package Jay\AwesomePostView\Tests
 */
class TestMenu extends TestCase {

	/**
	 * Holds the instance of metabox class.
	 *
	 * @since 1.0.0
	 *
	 * @var Metabox
	 */
	private $menu_instance;

	/**
	 * Sets up the fixture, for example, open a network connection.
	 *
	 * This method is called before each test.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function set_up() {
		$this->menu = new Menu();
	}

	/**
	 * Tests if admin menu is added successfully.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function test_admin_menu_added() {
		$this->menu->add_admin_menu();
		$this->assertNotEmpty( menu_page_url( 'apv' ) );
	}
}
