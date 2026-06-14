<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AIFG_Faq_Set {
	public function register_post_type() {
		$labels = array(
			'name'               => 'FAQ Sets',
			'singular_name'      => 'FAQ Set',
			'menu_name'          => 'FAQ Sets',
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New FAQ Set',
			'edit_item'          => 'Edit FAQ Set',
			'new_item'           => 'New FAQ Set',
			'view_item'          => 'View FAQ Set',
			'search_items'       => 'Search FAQ Sets',
			'not_found'          => 'No FAQ Sets found',
			'not_found_in_trash' => 'No FAQ Sets found in Trash'
		);

		$args = array(
			'labels'              => $labels,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => false, // Handled inside primary modular loader.
			'query_var'           => true,
			'rewrite'             => array( 'slug' => 'aifg-faq-set' ),
			'capability_type'     => 'post',
			'has_archive'         => false,
			'hierarchical'        => false,
			'menu_position'       => null,
			'supports'            => array( 'title' ),
			'show_in_rest'        => true
		);

		register_post_type( 'aifg_faq_set', $args );
	}
}