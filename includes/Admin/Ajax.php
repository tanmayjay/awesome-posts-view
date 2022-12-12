<?php

namespace Jay\AwesomePostView\Admin;

defined( 'ABSPATH' ) || exit; // Exit if called directly.

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
			$response   = wp_remote_get( $remote_url, array( 'timeout' => 15 ) );

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

		if ( array_key_exists( Settings::NUM_ROWS_INDEX, $_POST ) ) {
			$updated = $settings->update_single( Settings::NUM_ROWS_INDEX, intval( $_POST[ Settings::NUM_ROWS_INDEX ] ) );
		} elseif ( array_key_exists( Settings::HUMAN_DATE_INDEX, $_POST ) ) {
			$updated = $settings->update_single(
				Settings::HUMAN_DATE_INDEX,
				sanitize_text_field( wp_unslash( $_POST[ Settings::HUMAN_DATE_INDEX ] ) )
			);
		} elseif ( array_key_exists( Settings::EMAILS_INDEX, $_POST ) ) {
			$updated = $settings->update_single(
				Settings::EMAILS_INDEX,
				array_map( 'sanitize_email', (array) wp_unslash( $_POST[ Settings::EMAILS_INDEX ] ) )
			);
		} else {
			$this->send_error( array( 'message' => esc_html__( 'The provided settings key is not valid!', 'apv' ) ) );
		}

		if ( is_wp_error( $updated ) ) {
			$this->send_error( array( 'message' => $updated->get_error_message() ) );
		}

		$this->send_success(
			array(
				'data'    => $settings->get(),
				'message' => esc_html__( 'Settings updated successfully.', 'apv' ),
			)
		);
	}
}
