<?php

namespace Jay\AwesomePostView\Admin;

defined( 'ABSPATH' ) || exit; // Exit if called directly.

use Jay\AwesomePostView\Helper;

/**
 * Admin menu handler.
 *
 * @since 1.0.0
 *
 * @package Jay\AwesomePostView
 */
class Menu {

	/**
	 * Class constructor
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->hooks();
	}

	/**
	 * Registers all necessary hooks.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function hooks() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
	}

	/**
	 * Add Dokan admin menu
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function add_admin_menu() {
		$capability = Helper::get_admin_menu_cap();
		if ( ! current_user_can( $capability ) ) {
			return;
		}

		global $submenu;

		$slug       = 'apv';
		$page_title = __( 'Awesome Post View', 'apv' );

		$apv_menu = add_menu_page(
			$page_title,
			__( 'APV', 'apv' ),
			$capability,
			$slug,
			array( $this, 'render_admin_menu' ),
			'dashicons-tide',
			57
		);

		$submenu[ $slug ] = array( // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			array(
				__( 'Table', 'apv' ),
				$capability,
				Helper::get_submenu_url_base( 'table' ),
			),
			array(
				__( 'Graph', 'apv' ),
				$capability,
				Helper::get_submenu_url_base( 'graph' ),
			),
			array(
				__( 'Settings', 'apv' ),
				$capability,
				Helper::get_submenu_url_base( 'settings' ),
			),
		);

		add_action( $apv_menu, array( $this, 'enqueue_admin_scripts' ) );
	}

	/**
	 * Enqueues admin scripts.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueue_admin_scripts() {
		wp_enqueue_script( 'apv-admin' );
		wp_enqueue_style( 'apv-admin' );

		do_action( 'apv_enqueue_admin_scripts' );
	}

	/**
	 * Renders admin menu.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function render_admin_menu() {
		ob_start();
		?>
			<div class="wrap">
				<noscript>
					<strong>
						<?php esc_html_e( "We're sorry but this page cannot be rendered without JavaScript enabled. Please enable it to continue.", 'apv' ); ?>
					</strong>
				</noscript>
				<div id="apv-admin-app">
					<!-- Admin views will be injected here -->
				</div>
			</div>
		<?php
		ob_end_flush();
	}
}
