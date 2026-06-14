<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AIFG_Gutenberg_Block {
	public function init() {
		add_action( 'init', array( $this, 'register_faq_gutenberg_block' ) );
	}

	public function register_faq_gutenberg_block() {
		// Prevent activation crashes by ensuring the block metadata folder exists
		$block_json_dir = AIFG_PLUGIN_DIR . 'blocks/faq-block';
		
		if ( ! file_exists( $block_json_dir . '/block.json' ) ) {
			return;
		}

		// Standard WordPress production layout registration pattern using block.json path directly
		register_block_type( $block_json_dir, array(
			'render_callback' => array( $this, 'render_block_fallback_callback' ),
			'attributes'      => array(
				'selectedFaqSet' => array(
					'type'    => 'string',
					'default' => ''
				),
				'layoutStyle'    => array(
					'type'    => 'string',
					'default' => 'modern'
				)
			)
		));

		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ) );
	}

	public function enqueue_block_editor_assets() {
		wp_enqueue_script( 'aifg-block-js', AIFG_PLUGIN_URL . 'assets/js/block.js', array( 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components' ), AIFG_VERSION, true );
		wp_enqueue_style( 'aifg-block-editor-css', AIFG_PLUGIN_URL . 'assets/css/block.css', array(), AIFG_VERSION );
		
		// Map existing saved data sets into backend select values safely
		$faq_sets = get_posts( array( 'post_type' => 'aifg_faq_set', 'numberposts' => -1 ) );
		$options  = array( array( 'label' => '-- Choose Layout Target --', 'value' => '' ) );
		
		foreach ( $faq_sets as $set ) {
			$options[] = array( 'label' => $set->post_title, 'value' => (string)$set->ID );
		}

		wp_localize_script( 'aifg-block-js', 'aifg_block_vars', $options );
	}

	public function render_block_fallback_callback( $attributes ) {
		if ( empty( $attributes['selectedFaqSet'] ) ) {
			return '<p style="padding:15px; border:1px dashed #ccc;">Please select an FAQ Set in block setting panels.</p>';
		}

		$shortcode_instance = new AIFG_Shortcode();
		return $shortcode_instance->render_shortcode( array(
			'id'     => $attributes['selectedFaqSet'],
			'layout' => isset( $attributes['layoutStyle'] ) ? $attributes['layoutStyle'] : 'modern'
		));
	}
}