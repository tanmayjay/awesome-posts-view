<?php

namespace Jay\AwesomePostView\Admin;

defined( 'ABSPATH' ) || exit; // Exit if called directly.

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
	 * @param array $settings The array of settings.
	 *
	 * @return void
	 */
	private function set( $settings = null ) : void {
		$this->settings = is_null( $settings ) ? get_option( self::OPTION_KEY, array() ) : $settings;
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
	 * @param string $key (Optional) An specific key to parse value of.
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
	 * @param string $key (Optional) An specific key to parse value of.
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
	 * @param array $data Array of settings data.
	 *
	 * @return bool
	 */
	public function update( $data ) : bool {
		$this->set( $data );
		return update_option( self::OPTION_KEY, $data );
	}

	/**
	 * Updates a single key of settings.
	 *
	 * @since 1.0.0
	 *
	 * @param string         $key   Name of the settings key.
	 * @param int|bool|array $value Value of the settings key.
	 *
	 * @return true|\WP_Error
	 */
	public function update_single( $key, $value ) {
		switch ( $key ) {
			case self::NUM_ROWS_INDEX:
				$value = intval( $value );
				if ( $value < 1 || $value > 5 ) {
					return new \WP_Error( 'invalid_number_rows', __( 'Number of rows must be inclusively between 1 and 5', 'apv' ) );
				}
				break;

			case self::HUMAN_DATE_INDEX:
				$value = Helper::parse_bool( $value );
				break;

			case self::EMAILS_INDEX:
				$value = (array) $value;
				foreach ( $value as $email ) {
					if ( ! is_email( $email ) ) {
						return new \WP_Error( 'invalid_emails', __( 'Invalid email found! Please make sure all the emails are valid.', 'apv' ) );
					}
				}
				break;

			default:
				return new \WP_Error( 'invalid_key', __( 'The provided settings key is not valid!', 'apv' ) );
		}

		$settings = $this->get();
		if ( empty( $settings ) ) {
			$settings = $this->get_defaults();
		}

		$settings[ $key ] = $value;
		$updated          = $this->update( $settings );

		if ( ! $updated ) {
			return new \WP_Error( 'settings_update_error', __( 'Something went wrong. Could not update the settings!', 'apv' ) );
		}

		return true;
	}
}
