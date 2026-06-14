<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AIFG_Schema {
	public function inject_faq_schema() {
		// Strict structural mapping filter preventing parsing duplication scenarios on post grids.
		if ( ! is_singular() ) {
			return;
		}

		global $post;
		if ( ! $post ) {
			return;
		}

		// Scan standard tracking structures to see if a shortcode exists inside post content content loops.
		if ( has_shortcode( $post->post_content, 'aifaq' ) ) {
			preg_match_all( '/\[aifaq\s+(?:[^\]]*?\s+)?id=["\']([0-9]+)["\']/i', $post->post_content, $matches );
			
			if ( ! empty( $matches[1][0] ) ) {
				$faq_set_id = intval( $matches[1][0] );
				$faqs = get_post_meta( $faq_set_id, '_aifg_faq_data', true );

				if ( is_array( $faqs ) && ! empty( $faqs ) ) {
					$schema = array(
						'@context'   => 'https://schema.org',
						'@type'      => 'FAQPage',
						'mainEntity' => array()
					);

					foreach ( $faqs as $faq ) {
						$schema['mainEntity'][] = array(
							'@type'          => 'Question',
							'name'           => wp_strip_all_tags( $faq['question'] ),
							'acceptedAnswer' => array(
								'@type' => 'Answer',
								'text'  => wp_strip_all_tags( $faq['answer'] )
							)
						);
					}

					echo "\n\n";
					echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . '</script>' . "\n";
				}
			}
		}
	}
}