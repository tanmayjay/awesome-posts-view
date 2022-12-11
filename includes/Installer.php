<?php

namespace Jay\AwesomePostView;

defined( 'ABSPATH' ) || exit; // Exit if called directly

/**
 * Plugin installer.
 *
 * @since 1.0.0
 *
 * @package Jay\AwesomePostView
 */
class Installer {

    /**
     * Executes necessary setup for installing.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function run() : void {
        $this->add_version_info();
    }

    /**
     * Adds version inforrmation.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function add_version_info() : void {
        update_option( 'apv_version', APV_VERSION );
    }

    /**
     * Sets up default settings.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function setup_default_settings() : void {
        $settings = Admin\Settings::instance();

        if ( false === $settings->get() ) {
            $settings->update( $settings->get_defaults() );
        }
    }
}
