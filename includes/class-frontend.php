<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AIFG_Frontend {
	public function enqueue_frontend_assets() {
		$settings = get_option( 'aifg_settings', array() );
		
		// Only deploy stylesheet configurations when user toggled parameters to active.
		if ( ! empty( $settings['enable_faq_styles'] ) && '1' === $settings['enable_faq_styles'] ) {
			wp_enqueue_style( 'aifg-frontend-css', AIFG_PLUGIN_URL . 'assets/css/frontend.css', array(), AIFG_VERSION );
			wp_enqueue_script( 'aifg-frontend-js', AIFG_PLUGIN_URL . 'assets/js/frontend.js', array(), AIFG_VERSION, true );
		}
	}
}