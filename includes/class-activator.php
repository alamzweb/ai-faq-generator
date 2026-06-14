<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AIFG_Activator {
	public static function activate() {
		// Initialize explicit default structure with all features turned OFF.
		if ( false === get_option( 'aifg_settings' ) ) {
			$defaults = array(
				'enable_schema'       => '0',
				'enable_faq_styles'   => '0',
				'load_assets_globally'=> '0',
				'auto_insert'         => '0',
				'tracking_features'   => '0',
			);
			update_option( 'aifg_settings', $defaults );
		}
		
		// Force register custom post type structure to prevent early query 404s.
		$faq_set = new AIFG_Faq_Set();
		$faq_set->register_post_type();
		flush_rewrite_rules();
	}
}