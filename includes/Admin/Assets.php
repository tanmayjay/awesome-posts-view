<?php

namespace Jay\AwesomePostView\Admin;

defined( 'ABSPATH' ) || exit; // Exit if called directly

use Jay\AwesomePostView\Helper;

/**
 * Assets handler class.
 *
 * @since JBC_SINCE
 *
 * @package jay/jblog-contributors
 */
class Assets {

    /**
     * Class constructor.
     *
     * @since JBC_SINCE
     *
     * @return void
     */
    public function __construct() {
        $this->hooks();
    }

    /**
     * Registers all hooks.
     *
     * @since JBC_SINCE
     *
     * @return void
     */
    private function hooks() : void {
        add_action( 'init', array( $this, 'register_scripts' ) );
    }

    /**
     * Registers scripts and styles.
     *
     * @since JBC_SINCE
     *
     * @return void
     */
    public function register_scripts() : void {
        list( $version, $min ) = Helper::get_asset_data();

        wp_register_style(
            'apv-admin',
            APV_ASSETS . "/css/admin{$min}.css",
            array(),
            $version
        );
        wp_register_script(
            'apv-admin',
            APV_ASSETS . "/js/admin{$min}.js",
            array( 'jquery' ),
            $version,
            true
        );
        wp_localize_script(
            'apv-admin',
            'apvAdminData',
            array(
                'ajax' => array(
                    'url'   => admin_url( 'admin-ajax.php' ),
                    'nonce' => wp_create_nonce( 'apv-admin-nonce' ),
                    'prefix' => 'apv_',
                ),
            )
        );
    }
}
