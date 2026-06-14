<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="aifg-frontend-accordion-container aifg-layout-cards">
	<?php foreach ( $faqs as $faq ) : ?>
		<div class="aifg-card-item">
			<h4 class="aifg-card-title"><?php echo esc_html( $faq['question'] ); ?></h4>
			<p class="aifg-card-body"><?php echo esc_html( $faq['answer'] ); ?></p>
		</div>
	<?php endforeach; ?>
</div>