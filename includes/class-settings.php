<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AIFG_Settings {
	public static function register_plugin_settings() {
		register_setting( 'aifg_settings_group', 'aifg_settings', array( 'AIFG_Settings', 'sanitize_settings_callback' ) );
	}

	public static function sanitize_settings_callback( $input ) {
		$sanitized = array();
		$keys = array( 'enable_schema', 'enable_faq_styles', 'load_assets_globally', 'auto_insert', 'tracking_features' );
		
		foreach ( $keys as $key ) {
			$sanitized[ $key ] = ( isset( $input[ $key ] ) && '1' === $input[ $key ] ) ? '1' : '0';
		}
		
		return $sanitized;
	}
}