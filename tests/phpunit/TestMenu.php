<?php

namespace Jay\AwesomePostView\Tests;

use Jay\AwesomePostView\Helper;
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
	private $menu;

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
		add_filter( 'user_has_cap', array( $this, 'add_cap' ) );
		$this->menu->add_admin_menu();
		remove_filter( 'user_has_cap', array( $this, 'add_cap' ) );
		$this->assertNotEmpty( menu_page_url( 'apv' ) );
	}

	/**
	 * Adds admin menu capability to the current user.
	 *
	 * @since 1.0.0
	 *
	 * @param array $caps
	 *
	 * @return array
	 */
	public function add_cap( $caps ) {
		$caps[ Helper::get_admin_menu_cap() ] = true;
		return $caps;
	}
}
