<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AIFG_Shortcode {
	public function init() {
		add_shortcode( 'aifaq', array( $this, 'render_shortcode' ) );
	}

	public function render_shortcode( $atts ) {
		$args = shortcode_atts( array(
			'id'    => 0,
			'layout'=> 'modern'
		), $atts, 'aifaq' );

		$post_id = intval( $args['id'] );
		if ( ! $post_id || 'aifg_faq_set' !== get_post_type( $post_id ) ) {
			return '';
		}

		$faqs = get_post_meta( $post_id, '_aifg_faq_data', true );
		if ( ! is_array( $faqs ) || empty( $faqs ) ) {
			return '';
		}

		// Dynamic file template resolution isolation framework path patterns.
		$layout = in_array( $args['layout'], array( 'modern', 'minimal', 'cards', 'dark' ), true ) ? $args['layout'] : 'modern';
		$template_path = AIFG_PLUGIN_DIR . 'templates/' . $layout . '.php';

		// Track dynamic visual usage execution layers inside database records.
		$views = (int) get_post_meta( $post_id, '_aifg_faq_views_count', true );
		update_post_meta( $post_id, '_aifg_faq_views_count', $views + 1 );

		ob_start();
		if ( file_exists( $template_path ) ) {
			include $template_path;
		}
		return ob_get_clean();
	}
}