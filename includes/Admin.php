<?php

namespace Jay\AwesomePostView;

defined( 'ABSPATH' ) || exit; // Exit if called directly.

/**
 * Admin handler class.
 *
 * @since 1.0.0
 *
 * @package Jay\AwesomePostView
 */
class Admin {

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		if ( ! is_admin() ) {
			return;
		}

		$this->init_classes();
	}

	/**
	 * Instantiates necessary classes.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function init_classes() : void {
		new Admin\Assets();
		new Admin\Menu();
		new Admin\Ajax();
	}
}
