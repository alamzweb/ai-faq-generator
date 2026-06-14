<?php
/**
 * Safe complete cascade uninstallation option execution script layer database cleaner arrays.
 */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Clean and wipe option keys parameter arrays from option registries databases safely.
delete_option( 'aifg_settings' );

// Unlink and purge underlying custom structures posts cleanly using internal core arrays queries loops.
$faq_sets = get_posts( array(
	'post_type'   => 'aifg_faq_set',
	'numberposts' => -1,
	'post_status' => 'any'
));

foreach ( $faq_sets as $set ) {
	wp_delete_post( $set->ID, true );
}