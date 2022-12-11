<?php

namespace Jay\AwesomePostView\Admin;

defined( 'ABSPATH' ) || exit; // Exit if called directly

use Jay\AwesomePostView\Helper;
use Jay\AwesomePostView\Utilities\Traits\Ajax as AjaxHelper;

/**
 * Ajax handler.
 *
 * @since 1.0.0
 *
 * @package Jay\AwesomePostView
 */
class Ajax {

    use AjaxHelper;

    /**
     * Class constructor
     *
     * @since 1.0.0
     */
    public function __construct() {
        if ( ! wp_doing_ajax() ) {
            return;
        }

        $this->add_hooks(
            array(
                'get_basic_data',
                'get_settings',
                'update_settings',
            )
        );
    }

    /**
     * Gets remote data.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function get_basic_data() : void {
        if ( ! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['_wpnonce'] ), 'apv-admin-nonce' ) ) {
            $this->send_error( esc_html__( 'Nonce verification failed!', 'apv' ) );
        }

        if ( ! current_user_can( 'administrator' ) ) {
            $this->send_error( esc_html__( 'You do not have permission to access the data.', 'apv' ) );
        }

        $basic_data = get_transient( 'apv_basic_data' );

        if ( false === $basic_data ) {
            $remote_url = 'https://miusage.com/v1/challenge/2/static/';
            $response   = wp_remote_get( $remote_url, [ 'timeout' => 15 ] );

            if ( is_wp_error( $response ) || 200 !== $response['response']['code'] ) {
                $this->send_error();
            }

            $basic_data = wp_remote_retrieve_body( $response );
            set_transient( 'apv_basic_data', $basic_data, HOUR_IN_SECONDS );
        }

        $basic_data = json_decode( $basic_data, true );
        $this->send_success( $basic_data );
    }

    /**
     * Retrieves settings data.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function get_settings() : void {
        if ( ! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['_wpnonce'] ), 'apv-admin-nonce' ) ) {
            $this->send_error( esc_html__( 'Nonce verification failed!', 'apv' ) );
        }

        if ( ! current_user_can( 'administrator' ) ) {
            $this->send_error( esc_html__( 'You do not have permission to access the data.', 'apv' ) );
        }

        $settings = Settings::instance();
        $data     = $settings->get();

        if ( empty( $data ) ) {
            $data = $settings->get_defaults();
        }

        $this->send_success( $data );
    }

    /**
     * Updates single value of settings.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function update_settings() : void {
        if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['_wpnonce'] ), 'apv-admin-nonce' ) ) {
            $this->send_error( array( 'message' => esc_html__( 'Nonce verification failed!', 'apv' ) ) );
        }

        if ( ! current_user_can( 'administrator' ) ) {
            $this->send_error( array( 'message' => esc_html__( 'You do not have permission to access the data.', 'apv' ) ) );
        }

        $settings = Settings::instance();
        $data     = $settings->get();

        if ( empty( $data ) ) {
            $data = $settings->get_defaults();
        }

        if ( array_key_exists( Settings::NUM_ROWS_INDEX, $_POST ) ) {
            $num_rows = intval( $_POST[ Settings::NUM_ROWS_INDEX ] );

            if ( $num_rows < 1 || $num_rows > 5 ) {
                $this->send_error( array( 'message' => esc_html__( 'Number of rows must be inclusively between 1 and 5.', 'apv' ) ) );
            }

            $data[ Settings::NUM_ROWS_INDEX ] = $num_rows;
        } elseif ( array_key_exists( Settings::HUMAN_DATE_INDEX, $_POST ) ) {
            $human_date = Helper::parse_bool( sanitize_text_field( wp_unslash( $_POST[ Settings::HUMAN_DATE_INDEX ] ) ) );
            $data[ Settings::HUMAN_DATE_INDEX ] = $human_date;
        } elseif ( array_key_exists( Settings::EMAILS_INDEX, $_POST ) ) {
            $invalid_emails = [];

            $emails = array_map(
                function( $email ) use ( &$invalid_emails ) {
                    if ( ! is_email( $email ) ) {
                        $invalid_emails[] = $email;
                    }

                    return sanitize_email( wp_unslash( $email ) );
                },
                (array) $_POST[ Settings::EMAILS_INDEX ]
            );

            if ( ! empty( $invalid_emails ) ) {
                $this->send_error(
                    array(
                        'message' => sprintf(
                            esc_html__(
                                'The emails: %s are not valid. Please provide correct emails.',
                                'apv'
                            ),
                            implode( ', ', $invalid_emails )
                        )
                    )
                );
            }

            $data[ Settings::EMAILS_INDEX ] = $emails;
        } else {
            $this->send_error( array( 'message' => esc_html__( 'No valid setting was provided!', 'apv' ) ) );
        }

        $settings_updated = $settings->update( $data );

        if ( $settings_updated ) {
            $this->send_success(
                array(
                    'data' => $data,
                    'message' => esc_html__( 'Settings updated successfully.', 'apv' ),
                )
            );
        } else {
            $this->send_error( array( 'message' => esc_html__( 'Settings could not be updated!', 'apv' ) ) );
        }
    }
}
