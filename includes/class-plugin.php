<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AIFG_Plugin {
	protected $admin;
	protected $frontend;
	protected $shortcode;
	protected $gutenberg_block;
	protected $ajax;

	public function __construct() {
		$this->admin           = new AIFG_Admin();
		$this->frontend        = new AIFG_Frontend();
		$this->shortcode       = new AIFG_Shortcode();
		$this->gutenberg_block = new AIFG_Gutenberg_Block();
		$this->ajax            = new AIFG_Ajax();
	}

	public function run() {
		// Orchestrate actions and core bindings.
		$faq_set = new AIFG_Faq_Set();
		add_action( 'init', array( $faq_set, 'register_post_type' ) );

		if ( is_admin() ) {
			add_action( 'admin_menu', array( $this->admin, 'add_plugin_admin_menu' ) );
			add_action( 'admin_enqueue_scripts', array( $this->admin, 'enqueue_admin_assets' ) );
			add_action( 'admin_init', array( 'AIFG_Settings', 'register_plugin_settings' ) );
			$this->ajax->init();
		} else {
			add_action( 'wp_enqueue_scripts', array( $this->frontend, 'enqueue_frontend_assets' ) );
		}

		$this->shortcode->init();
		$this->gutenberg_block->init();

		// Schema injection implementation filter.
		$settings = get_option( 'aifg_settings', array() );
		if ( ! empty( $settings['enable_schema'] ) && '1' === $settings['enable_schema'] ) {
			$schema = new AIFG_Schema();
			add_action( 'wp_footer', array( $schema, 'inject_faq_schema' ) );
		}
	}
}