<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AIFG_Ajax {
	public function init() {
		add_action( 'wp_ajax_aifg_trigger_generation_action', array( $this, 'ajax_handle_generation' ) );
		add_action( 'wp_ajax_aifg_save_faq_set_action', array( $this, 'ajax_handle_save_set' ) );
	}

	public function ajax_handle_generation() {
		// Strict Security Capability and Nonce Auditing Layers.
		check_ajax_referer( 'aifg_admin_nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => 'Unauthorized operation permission access restriction.' ) );
		}

		$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
		if ( ! $post_id ) {
			wp_send_json_error( array( 'message' => 'Invalid structural target node selected.' ) );
		}

		$generator = new AIFG_Generator();
		$faqs      = $generator->generate_local_faqs( $post_id );

		wp_send_json_success( array( 'faqs' => $faqs ) );
	}

	public function ajax_handle_save_set() {
		check_ajax_referer( 'aifg_admin_nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => 'Unauthorized operation permission access restriction.' ) );
		}

		$title = isset( $_POST['title'] ) ? sanitize_text_field( wp_unslash( $_POST['title'] ) ) : '';
		$faqs  = isset( $_POST['faqs'] ) ? array_map( function( $item ) {
			return array(
				'question' => sanitize_text_field( $item['question'] ),
				'answer'   => sanitize_textarea_field( $item['answer'] )
			);
		}, $_POST['faqs'] ) : array();

		if ( empty( $title ) ) {
			wp_send_json_error( array( 'message' => 'FAQ Set reference label is mandatory.' ) );
		}

		// Programmatically insert localized Custom Post Type array logs.
		$new_id = wp_insert_post( array(
			'post_title'  => $title,
			'post_status' => 'publish',
			'post_type'   => 'aifg_faq_set'
		));

		if ( is_wp_error( $new_id ) ) {
			wp_send_json_error( array( 'message' => 'Failed to create data profiles cleanly inside database rows.' ) );
		}

		update_post_meta( $new_id, '_aifg_faq_data', $faqs );
		wp_send_json_success( array( 'message' => 'FAQ Set saved successfully!', 'id' => $new_id ) );
	}
}