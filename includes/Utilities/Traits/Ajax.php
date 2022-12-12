<?php

namespace Jay\AwesomePostView\Utilities\Traits;

defined( 'ABSPATH' ) || exit; // Exit if called directly.

/**
 * Ajax helper trait.
 *
 * @since 1.0.0
 *
 * @package Jay\AwesomePostView
 */
trait Ajax {

	/**
	 * Prefix for the ajax hooks.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $hook_prefix = 'apv_';

	/**
	 * Class for the callback method.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	private $callback_class = null;

	/**
	 * Sets the hook prefix.
	 *
	 * @since 1.0.0
	 *
	 * @param string $prefix Prefix name of the hooks.
	 *
	 * @return self
	 */
	protected function set_prefix( $prefix ) : self {
		$this->hook_prefix = $prefix;
		return $this;
	}

	/**
	 * Sets the callback class.
	 *
	 * @since 1.0.0
	 *
	 * @param object $callback_class class instance for the callback method.
	 *
	 * @return self
	 */
	protected function set_class( $callback_class ) : self {
		$this->callback_class = $callback_class;
		return $this;
	}

	/**
	 * Retrieves the callback class.
	 *
	 * @since 1.0.0
	 *
	 * @return object
	 */
	private function get_class() : object {
		if ( empty( $this->callback_class ) ) {
			$this->callback_class = $this;
		}
		return $this->callback_class;
	}

	/**
	 * Adds a hook to register.
	 *
	 * @since 1.0.0
	 *
	 * @param string $hook  The hook name.
	 *
	 * @return void
	 */
	protected function add_hook( $hook ) : void {
		add_action( "wp_ajax_{$this->hook_prefix}{$hook}", array( $this->get_class(), $hook ) );
	}

	/**
	 * Adds multiple hooks to register.
	 *
	 * @since 1.0.0
	 *
	 * @param string[] $hooks List of the hooks.
	 *
	 * @return void
	 */
	protected function add_hooks( $hooks ) : void {
		foreach ( $hooks as $hook ) {
			add_action( "wp_ajax_{$this->hook_prefix}{$hook}", array( $this->get_class(), $hook ) );
		}
	}

	/**
	 * Sends JSON success response.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $response               Response to be send through ajax.
	 * @param int   $status_code (Optional) Status code of the response data.
	 * @param int   $option      (Optional) Option for the response.
	 *
	 * @return void
	 */
	protected function send_success( $response = null, $status_code = null, $option = 0 ) : void {
		wp_send_json_success( $response, $status_code, $option );
	}

	/**
	 * Sends JSON error response.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $response    (Optional) Response to be send through ajax.
	 * @param int   $status_code (Optional) Status code of the response data.
	 * @param int   $option      (Optional) Option for the response.
	 *
	 * @return void
	 */
	protected function send_error( $response = null, $status_code = null, $option = 0 ) : void {
		wp_send_json_error( $response, $status_code, $option );
	}
}
