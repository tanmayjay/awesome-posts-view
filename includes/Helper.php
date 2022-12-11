<?php

namespace Jay\AwesomePostView;

defined( 'ABSPATH' ) || exit; // Exit if called directly

/**
 * Helper class.
 *
 * @since 1.0.0
 *
 * @package Jay\AwesomePostView
 */
class Helper {

    /**
     * Retrieves capability for admin menu.
     *
     * @since 1.0.0
     *
     * @return string
     */
    public static function get_admin_menu_cap() : string {
        return apply_filters( 'apv_admin_menu_cap', 'administrator' );
    }

    /**
     * Retrieves admin submenu url base.
     *
     * @since 1.0.0
     *
     * @param string $submenu
     * @param string $menu
     *
     * @return string
     */
    public static function get_submenu_url_base( $submenu = '', $menu = 'apv' ) : string {
        return apply_filters( 'apv_submenu_url_base', "admin.php?page={$menu}#/{$submenu}", $submenu, $menu );
    }

    /**
     * Returns script version and minified syntax.
     *
     * @since 1.0.0
     *
     * @return array
     */
    public static function get_asset_data() : array {
        if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
            $version = time();
            $min     = '';
        } else {
            $version = APV_VERSION;
            $min     = '.min';
        }

        return array(
            $version,
            $min,
        );
    }

    /**
     * Converts a value to its boolean form.
     *
     * @since 1.0.0
     *
     * @param string|int $value
     *
     * @return bool
     */
    public static function parse_bool( $value ) {
        return is_bool( $value ) ? $value : ( 'yes' === strtolower( $value ) || 1 === $value || 'true' === strtolower( $value ) || '1' === $value );
    }
}
