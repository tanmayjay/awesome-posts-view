<?php

/**
 * Plugin Name: Awesome Post View
 * Description: A plugin to show posts beautifully.
 * Plugin URI: https://github.com/tanmayjay/awesome-post-view
 * Author: Tanmay Jay
 * Author URI: https://jktanmay.com
 * Version: 1.0.0
 * License: GPL2
 * Text Domain: apv
 * Domain Path: /languages/
 * Requires at least: 5.8
 * Requires PHP: 7.2
 *
 * Copyright (c) 2022 Tanmay Kirtania (email: jktanmay@gmail.com). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, see <https://www.gnu.org/licenses/>.
 * **********************************************************************
 */

defined( 'ABSPATH' ) || exit; // Exit if called directly

/**
 * Main class
 *
 * @package Jay\AwesomePostView
 */
final class AwesomePostView {

    /**
     * Plugin version.
     *
     * @since 1.0.0
     *
     * @var string
     */
    private $version = '1.0.0';

    /**
     * Minimum PHP version for the plugin.
     *
     * @since 1.0.0
     *
     * @var string
     */
    public $minimum_php = '7.2';

    /**
     * The class instance.
     *
     * @since 1.0.0
     *
     * @var self
     */
    private static $instance = null;

    /**
     * Instantiates the class.
     *
     * @since 1.0.0
     *
     * @return self
     */
    public static function init() : self {
        if ( ! self::$instance ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * CLass constructor.
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function __construct() {
        $this->includes();
        $this->constants();
        $this->init_plugin();
    }

    /**
     * Includes necessary files.
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function includes() : void {
        require_once __DIR__ . '/vendor/autoload.php';
    }

    /**
     * Defines all the necessary constants.
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function constants() : void {
        define( 'APV_VERSION', $this->version );
        define( 'APV_FILE', __FILE__ );
        define( 'APV_DIR', dirname( APV_FILE ) );
        define( 'APV_TEMPLATES', APV_DIR . '/templates' );
        define( 'APV_INC', APV_DIR . '/includes' );
        define( 'APV_LIB', plugins_url( 'lib', APV_FILE ) );
        define( 'APV_ASSETS', plugins_url( 'assets', APV_FILE ) );
    }

    /**
     * Initiates the functionalities of the plugin.
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function init_plugin() : void {
        $this->init_hooks();
        $this->init_classes();

        do_action( 'apv_loaded' );
    }

    /**
     * Instanciates the classes.
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function init_classes() : void {
    }

    /**
     * Initiates the hooks.
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function init_hooks() : void {
        register_activation_hook( APV_FILE, array( $this, 'activate' ) );

        add_action( 'init', array( $this, 'setup_localization' ) );
    }

    /**
     * Executes activation requirements.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function activate() : void {
        if ( ! $this->has_minimum_php_version() ) {
            exit;
        }

        $installer = Jay\AwesomePostView\Installer();
        $installer->run();
    }

    /**
     * Setups the localization.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function setup_localization() {
        load_plugin_textdomain( 'apv', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    /**
     * Check if the PHP version is supported.
     *
     * @since 1.0.0
     *
     * @return bool
     */
    public function has_minimum_php_version() : bool {
        return version_compare( PHP_VERSION, $this->minimum_php, '>=' );
    }
}


/**
 * Returns the instance of the main class.
 *
 * @since 1.0.0
 *
 * @return AwesomePostView
 */
function apv() : AwesomePostView {
    return AwesomePostView::init();
}

// Kick-off the plugin
apv();
