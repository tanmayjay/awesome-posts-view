<?php

namespace Jay\AwesomePostView\Admin;

defined( 'ABSPATH' ) || exit; // Exit if called directly

use Jay\AwesomePostView\Helper;

/**
 * Settings handler class.
 *
 * @since 1.0.0
 *
 * @package Jay\AwesomePostView
 */
class Settings {

    /**
     * Option key for settings.
     *
     * @since 1.0.0
     *
     * @var string
     */
    const OPTION_KEY = 'test_project_option';

    /**
     * Settings index for number of rows.
     *
     * @since 1.0.0
     *
     * @var string
     */
    const NUM_ROWS_INDEX = 'numrows';

    /**
     * Settings index for human date.
     *
     * @since 1.0.0
     *
     * @var string
     */
    const HUMAN_DATE_INDEX = 'humandate';

    /**
     * Settings index for emails.
     *
     * @since 1.0.0
     *
     * @var string
     */
    const EMAILS_INDEX = 'emails';

    /**
     * Holds the settings data.
     *
     * @since 1.0.0
     *
     * @var array
     */
    private $settings;

    /**
     * Holds the default settings data.
     *
     * @since 1.0.0
     *
     * @var array
     */
    private $defaults;

    /**
     * The instance of the class.
     *
     * @since 1.0.0
     *
     * @var self
     */
    private static $instance = null;

    /**
     * Class constructor.
     *
     * @since 1.0.0
     */
    private function __construct() {
        $this->set_defaults();
        $this->set();
    }

    /**
     * Retrieves the class instance.
     *
     * @since 1.0.0
     *
     * @return self
     */
    public static function instance() : self {
        if ( ! self::$instance ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Sets the settings data.
     *
     * @since 1.0.0
     *
     * @param array $settings
     *
     * @return void
     */
    private function set( $settings = null ) : void {
        $this->settings = is_null( $settings ) ? get_option( self::OPTION_KEY, [] ) : $settings;
    }

    /**
     * Sets the settings data.
     *
     * @since 1.0.0
     *
     * @return void
     */
    private function set_defaults() : void {
        $this->defaults = array(
            self::NUM_ROWS_INDEX   => 5,
            self::HUMAN_DATE_INDEX => true,
            self::EMAILS_INDEX     => array(
                get_bloginfo( 'admin_email' ),
            ),
        );
    }

    /**
     * Retrieves settings data.
     *
     * @since 1.0.0
     *
     * @param string $key (Optional)
     *
     * @return mixed
     */
    public function get( $key = null ) {
        if ( is_null( $key ) ) {
            return $this->settings;
        }

        if ( isset( $this->settings[ $key ] ) ) {
            return $this->settings[ $key ];
        }

        return '';
    }

    /**
     * Retrieves default settings data.
     *
     * @since 1.0.0
     *
     * @param string $key (Optional)
     *
     * @return mixed
     */
    public function get_defaults( $key = null ) {
        if ( is_null( $key ) ) {
            return $this->defaults;
        }

        if ( isset( $this->defaults[ $key ] ) ) {
            return $this->defaults[ $key ];
        }

        return '';
    }

    /**
     * Retrieves number of rows.
     *
     * @since 1.0.0
     *
     * @return int
     */
    public function get_number_of_rows() : int {
        return ! empty( $this->settings[ self::NUM_ROWS_INDEX ] )
            ? absint( $this->settings[ self::NUM_ROWS_INDEX ] )
            : absint( $this->defaults[ self::NUM_ROWS_INDEX ] );
    }

    /**
     * Retrieves flag for human readble date.
     *
     * @since 1.0.0
     *
     * @return bool
     */
    public function show_human_readable_date() : bool {
        $human_date = array_key_exists( self::HUMAN_DATE_INDEX, $this->settings )
            ? $this->settings[ self::HUMAN_DATE_INDEX ]
            : $this->defaults[ self::HUMAN_DATE_INDEX ];

        return Helper::parse_bool( $human_date );
    }

    /**
     * Retrieves emails.
     *
     * @since 1.0.0
     *
     * @return array
     */
    public function get_emails() : array {
        return ! array_key_exists( self::EMAILS_INDEX, $this->settings )
            ? (array) $this->defaults[ self::EMAILS_INDEX ]
            : ( empty( $this->settings[ self::EMAILS_INDEX ] ) ? array() : (array) $this->settings[ self::EMAILS_INDEX ] );
    }

    /**
     * Updates settings.
     *
     * @since 1.0.0
     *
     * @param array $data
     *
     * @return bool
     */
    public function update( $data ) : bool {
        $this->set( $data );
        return update_option( self::OPTION_KEY, $data );
    }
}