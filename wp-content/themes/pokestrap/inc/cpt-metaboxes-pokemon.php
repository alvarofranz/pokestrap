<?php

namespace pokestrap\theme;

/**
 * Responsible for registering the metaboxes for the pokemon custom post type.
 *
 * @return void
 */
function register_pokemon_metaboxes() {
	add_meta_box(
		'metabox_pokemon_information',
		'Pokemon Information',
		'pokestrap\theme\metabox_pokemon_information',
		'pokemon',
		'normal',
	);
}

add_action( 'add_meta_boxes', 'pokestrap\theme\register_pokemon_metaboxes' );


/**
 * Responisble for displaying the metabox for the pokemon weight.
 *
 * @return void
 */
function metabox_pokemon_information() {
	global $post;
	wp_nonce_field( basename( __FILE__ ), 'pokemon_information_nonce' );

	$pokemon_weight    = get_post_meta( $post->ID, 'pokemon_weight', true );
	$pokemon_index_old = get_post_meta( $post->ID, 'pokemon_index_old', true );
	$pokemon_index_new = get_post_meta( $post->ID, 'pokemon_index_new', true );

	echo '
    <input type="number" min="0" name="pokemon_weight" value="' . esc_attr( $pokemon_weight ) . '">
    <input type="number" min="0" name="pokemon_index_old" value="' . esc_attr( $pokemon_index_old ) . '">
    <input type="number" min="0" name="pokemon_index_new" value="' . esc_attr( $pokemon_index_new ) . '">
    ';
}


/**
 * Responsible for saving the metaboxes values.
 *
 * @param int $post_id The post ID.
 * @return int
 */
function save_pokemon_metaboxes_values( $post_id ) {

	// Return if autosaving.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return $post_id;
	}

	// Return if the user doesn't have permissions.
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return $post_id;
	}

	// Return if the nonce is not valid.
	if ( ! isset($_POST['pokemon_information_nonce']) || ! wp_verify_nonce( $_POST['pokemon_information_nonce'], basename( __FILE__ ) ) ) {
		return $post_id;
	}

	// For the pokemon weight.
	if ( isset( $_POST['pokemon_weight'] ) ) {
		update_post_meta( $post_id, 'pokemon_weight', $_POST['pokemon_weight'] );
	}

	return $post_id;
}

add_action( 'save_post_pokemon', 'pokestrap\theme\save_pokemon_metaboxes_values', 1, 2 );
