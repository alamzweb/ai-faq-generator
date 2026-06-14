<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="aifg-frontend-accordion-container aifg-layout-dark">
	<?php foreach ( $faqs as $faq ) : ?>
		<div class="aifg-accordion-item">
			<button class="aifg-accordion-trigger" type="button"><?php echo esc_html( $faq['question'] ); ?></button>
			<div class="aifg-accordion-content">
				<p><?php echo esc_html( $faq['answer'] ); ?></p>
			</div>
		</div>
	<?php endforeach; ?>
</div>