<?php

namespace Jay\AwesomePostView\Tests;

use Jay\AwesomePostView\Admin\Settings;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

/**
 * Settings methods test case.
 *
 * @since 1.0.0
 *
 * @package Jay\AwesomePostView\Tests
 */
class TestSettings extends TestCase {

	/**
	 * Holds the instance of metabox class.
	 *
	 * @since 1.0.0
	 *
	 * @var Settings
	 */
	private $settings;

	/**
	 * Sets up the fixture, for example, open a network connection.
	 *
	 * This method is called before each test.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function set_up() {
		$this->settings = Settings::instance();
	}

	/**
	 * Tests if settings get updated successfully.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function test_update_settings() {
		$data = array(
			Settings::NUM_ROWS_INDEX   => 2,
			Settings::HUMAN_DATE_INDEX => true,
			Settings::EMAILS_INDEX	   => array( 'test@mail.com' ),
		);

		$updated = $this->settings->update( $data );
		$this->assertTrue( $updated );
	}

	/**
	 * Tests if getting number of rows returns valid value.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function test_update_single() {
		$updated = $this->settings->update_single( Settings::NUM_ROWS_INDEX, 3 );
		$this->assertTrue( $updated );

		$updated = $this->settings->update_single( Settings::HUMAN_DATE_INDEX, false );
		$this->assertTrue( $updated );

		$updated = $this->settings->update_single( Settings::EMAILS_INDEX, array( 'test2@mail.com' ) );
		$this->assertTrue( $updated );
	}

	/**
	 * Tests if getting number of rows returns valid value.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function test_get_number_of_rows() {
		$number_of_rows = $this->settings->get_number_of_rows();
		$this->assertSame( 3, $number_of_rows );
	}

	/**
	 * Tests if getting human date flag returns valid value.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function test_get_human_date() {
		$show_human_date = $this->settings->show_human_readable_date();
		$this->assertFalse( $show_human_date );
	}

	/**
	 * Tests if getting emails returns valid value.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function test_get_emails() {
		$emails = $this->settings->get_emails();
		$this->assertSame( array( 'test2@mail.com' ), $emails );
	}

	/**
	 * Test wrong number of rows is validated.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function test_wrong_number_of_rows_value() {
		$updated = $this->settings->update_single( Settings::NUM_ROWS_INDEX, 0 );
		$this->assertInstanceOf( 'WP_Error', $updated, 'When number of rows is <1' );
		$this->assertSame( 'invalid_number_rows', $updated->get_error_code() );

		$updated = $this->settings->update_single( Settings::NUM_ROWS_INDEX, 6 );
		$this->assertInstanceOf( 'WP_Error', $updated, 'When number of rows is >5' );
		$this->assertSame( 'invalid_number_rows', $updated->get_error_code() );
	}

	/**
	 * Test wrong email is validated.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function test_wrong_emails_value() {
		$updated = $this->settings->update_single( Settings::EMAILS_INDEX, array( 'email@mail.com', 'email2' ) );
		$this->assertInstanceOf( 'WP_Error', $updated );
		$this->assertSame( 'invalid_emails', $updated->get_error_code() );
	}

	/**
	 * Test if humandate value is boolean.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function test_humandate_value_is_boolean() {
		$this->settings->update_single( Settings::HUMAN_DATE_INDEX, 'yes' );
		$this->assertTrue( $this->settings->show_human_readable_date() );

		$this->settings->update_single( Settings::HUMAN_DATE_INDEX, 'random' );
		$this->assertFalse( $this->settings->show_human_readable_date() );
	}

	/**
	 * Test if wrong settings key is discarded.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function test_wrong_settings_key_not_saved() {
		$updated = $this->settings->update_single( 'invalid_key', 'yes' );
		$this->assertInstanceOf( 'WP_Error', $updated );
		$this->assertSame( 'invalid_key', $updated->get_error_code() );
	}
}
