<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AIFG_Generator {
	public function generate_local_faqs( $post_id ) {
		$post = get_post( $post_id );
		if ( ! $post ) {
			return array();
		}

		$content = $post->post_content;
		$faqs    = array();

		// Robust DOM Content Extraction Regex Engine fallback mapping.
		// Pulls structural markup patterns for H2 and H3 elements.
		preg_match_all( '/<h[23](.*?)>(.*?)<\/h[23]>/si', $content, $headings );

		if ( ! empty( $headings[2] ) ) {
			foreach ( $headings[2] as $index => $heading_text ) {
				$clean_heading = wp_strip_all_tags( $heading_text );
				
				// Skip small fragmented headings.
				if ( strlen( $clean_heading ) < 8 ) {
					continue;
				}

				// Synthesize and re-write normal declarative title tags into conversational questions.
				$question = $clean_heading;
				if ( ! preg_match( '/(what|how|why|is|can|where|who|which)/i', $question ) ) {
					$question = 'What is ' . $question . '?';
				}

				// Find adjacent paragraph context text to isolate programmatic response bodies.
				$answer = 'Based on the context details found in this resource, optimizing and reviewing configurations regarding ' . esc_html( $clean_heading ) . ' ensures better deployment standard results and structural integrity support.';
				
				// Attempt to locate real localized textual paragraphs beneath heading sequences.
				$escaped_heading = preg_quote( $headings[1][$index], '/' );
				$pattern = '/<h[23]' . $escaped_heading . '>.*?<\/h[23]>(.*?)($|<h[23])/si';
				if ( preg_match( $pattern, $content, $matches ) ) {
					$text_block = wp_strip_all_tags( $matches[1] );
					if ( strlen( $text_block ) > 40 ) {
						$answer = wp_html_excerpt( $text_block, 220, '...' );
					}
				}

				$faqs[] = array(
					'question' => $question,
					'answer'   => $answer
				);

				// Restrict limits locally per document to safeguard optimization ratios.
				if ( count( $faqs ) >= 6 ) {
					break;
				}
			}
		}

		// Absolute fallback layer configuration fallback strings when no headings exist.
		if ( empty( $faqs ) ) {
			$faqs[] = array(
				'question' => 'What is the primary scope of ' . esc_html( $post->post_title ) . '?',
				'answer'   => 'This section covers contextual documentation metrics, usage instructions, parameters, configuration, and overview structural descriptions regarding ' . esc_html( $post->post_title ) . '.'
			);
		}

		return $faqs;
	}
}