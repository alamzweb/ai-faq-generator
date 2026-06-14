<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AIFG_Shortcode {
	public function init() {
		add_shortcode( 'ai_faq', array( $this, 'render_shortcode' ) );
	}

	public function render_shortcode( $atts ) {
		$pairs = shortcode_atts( array(
			'id'     => '',
			'layout' => 'modern'
		), $atts );

		if ( empty( $pairs['id'] ) ) {
			return '';
		}

		$post_id = intval( $pairs['id'] );
		$faqs    = get_post_meta( $post_id, '_aifg_faq_data', true );

		if ( ! $faqs || ! is_array( $faqs ) ) {
			return '<p>' . __( 'No FAQ data found.', 'ai-faq-generator' ) . '</p>';
		}

		// Look up the plugin configuration settings safely
		$settings = get_option( 'aifg_settings', array() );
		$schema_html = '';

		// Automatically bundle and inject valid JSON-LD structure right into the page context string
		if ( ! isset( $settings['enable_schema'] ) || '1' === $settings['enable_schema'] ) {
			$schema_data = array(
				'@context'   => 'https://schema.org',
				'@type'      => 'FAQPage',
				'mainEntity' => array()
			);

			foreach ( $faqs as $faq ) {
				$schema_data['mainEntity'][] = array(
					'@type'          => 'Question',
					'name'           => strip_tags( $faq['question'] ),
					'acceptedAnswer' => array(
						'@type'  => 'Answer',
						'text'   => strip_tags( $faq['answer'] )
					)
				);
			}

			$schema_html = "\n" . '<script type="application/ld+json">' . wp_json_encode( $schema_data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . '</script>' . "\n";
		}

		// Locate and load the physical styling template wrapper
		$template_path = AIFG_PLUGIN_DIR . 'templates/' . sanitize_file_name( $pairs['layout'] ) . '.php';
		
		if ( ! file_exists( $template_path ) ) {
			$template_path = AIFG_PLUGIN_DIR . 'templates/modern.php';
		}

		ob_start();
		include $template_path;
		$layout_html = ob_get_clean();

		// Append the clean, auto-generated structural SEO block to the layout presentation markup
		return $layout_html . $schema_html;
	}
}