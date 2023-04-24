<?php
/**
 * Responsible for getting the old Pokemon index post meta.
 *
 * @package Pokestrap
 */

add_action( 'wp_ajax_get_old_pokemon_index', 'get_old_pokemon_index_callback' );
add_action( 'wp_ajax_nopriv_get_old_pokemon_index', 'get_old_pokemon_index_callback' );

/**
 * Get old Pokemon index post meta.
 */
function get_old_pokemon_index_callback() {
	// Get post ID from AJAX request.
	$post_id = isset( $_POST['post_id'] ) ? sanitize_text_field( wp_unslash( $_POST['post_id'] ) ) : '';

	// Get old index post meta.
	$old_index = get_post_meta( $post_id, 'pokemon_index_old', true );

	// Send old index as AJAX response.
	wp_send_json( $old_index );
}
