<?php
/**
 * Responsible for generating a random Pokemon and creating a new custom post type.
 *
 * @package pokestrap
 */

namespace pokestrap\theme;

// Make sure the user is logged in and has post creation permission.
if ( ! is_user_logged_in() || ! current_user_can( 'publish_posts' ) ) {
	wp_die( 'You do not have permission to generate a Pokemon.' );
}

// Include necessary functions and classes.
require_once ABSPATH . 'wp-admin/includes/post.php';
require_once ABSPATH . 'wp-admin/includes/file.php';

/**
 * Responsible for generating a random Pokemon and creating a new custom post type.
 *
 * @return int|false The ID of the new Pokemon post.
 */
function create_random_pokemon_post() {

	// Fetch a random Pokemon from the API.
	$random_pokemon_id = wp_rand( 1, 898 ); // There are 898 Pokemon in total as of Gen VIII.
	$pokemon_data      = fetch_api_data( 'https://pokeapi.co/api/v2/pokemon/' . $random_pokemon_id );

	if ( ! $pokemon_data ) {
		return false;
	}

	// If Pokemon already exists as a custom post, skip and get another one.
	if ( post_exists( $pokemon_data['name'], '', 'pokemon' ) ) {
		// Call the function again to create a new Pokemon post.
		create_random_pokemon_post();
	}

	// Get the data we need for the new custom post type.
	$pokemon_name        = $pokemon_data['name'];
	$pokemon_description = get_flavor_text_in_language( $pokemon_data['species']['url'], 'en' );
	$pokemon_image_url   = $pokemon_data['sprites']['front_default'];
	$pokemon_weight      = $pokemon_data['weight'];
	$pokemon_new_index   = $pokemon_data['id'];
	$pokemon_old_index   = $pokemon_data['order'];

	if ( ! $pokemon_description ) {
		$pokemon_description = 'No description available.';
	}

	// Get the types and attacks (called moves in the API) for the new Pokemon.
	$types = array();
	foreach ( $pokemon_data['types'] as $type_data ) {
		$type = $type_data['type']['name'];
		$term = get_term_by( 'name', $type, 'type' );
		if ( $term ) {
			$term_id = $term->term_id;
		} else {
			$term_args = array(
				'slug' => $type,
			);
			$term      = wp_insert_term( $type, 'type', $term_args );
			$term_id   = $term['term_id'];
		}
		$types[] = $term_id;
	}

	$attacks = array();
	$counter = 0;
	foreach ( $pokemon_data['moves'] as $move_data ) {
		$counter ++;
		if ( $counter > 4 ) {
			break;
		}
		$move_name = $move_data['move']['name'];
		$term      = get_term_by( 'name', $move_name, 'attack' );

		if ( $term ) {
			$term_id = $term->term_id;
		} else {
			$term_args = array(
				'description' => get_flavor_text_in_language( $move_data['move']['url'], 'en' ),
				'slug'        => $move_name,
			);
			$term      = wp_insert_term( $move_name, 'attack', $term_args );
			$term_id   = $term['term_id'];
		}
		$attacks[] = $term_id;
	}

	// Create a new post object and populate it with data.
	$new_post = array(
		'post_title'   => ucfirst( $pokemon_name ),
		'post_name'    => sanitize_title( $pokemon_name ),
		'post_content' => '<p>' . $pokemon_description . '</p>',
		'post_status'  => 'publish',
		'post_type'    => 'pokemon',
		'tax_input'    => array(
			'type'   => $types,
			'attack' => $attacks,
		),
		'meta_input'   => array(
			'pokemon_weight'    => $pokemon_weight,
			'pokemon_index_new' => $pokemon_new_index,
			'pokemon_index_old' => $pokemon_old_index,
		),
	);

	// Insert the post into the database.
	$new_post_id = wp_insert_post( $new_post );

	// If the post was successfully inserted, add the featured image.
	if ( $new_post_id ) {
		// Get the upload directory and initialize the WP_Filesystem.
		$upload_dir = wp_upload_dir();

		// Upload the image and set it as the featured image for the post.
		if ( ! empty( $pokemon_image_url ) ) {
			$image_name       = sanitize_file_name( $pokemon_name ) . '.png';
			$unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name );
			$filename         = $upload_dir['path'] . '/' . $unique_file_name;
			$image_data       = wp_remote_retrieve_body( wp_remote_get( $pokemon_image_url ) );
			if ( false !== file_put_contents( $filename, $image_data ) ) {
				$wp_filetype   = wp_check_filetype( $filename, null );
				$attachment    = array(
					'post_mime_type' => $wp_filetype['type'],
					'post_title'     => sanitize_file_name( $pokemon_name ),
					'post_content'   => '',
					'post_status'    => 'inherit',
					'guid'           => $upload_dir['url'] . '/' . $unique_file_name,
				);
				$attachment_id = wp_insert_attachment( $attachment, $filename, $new_post_id );
				if ( ! is_wp_error( $attachment_id ) ) {
					set_post_thumbnail( $new_post_id, $attachment_id );
				}
			}
		}
	}

	// Redirect to the post page in the frontend.
	wp_redirect( get_permalink( $new_post_id ) );
	exit;

}

create_random_pokemon_post();

exit;


/**
 * Get the flavor text for a move in a given language.
 *
 * @param string $move_url The URL for the move.
 * @param string $language The language to get the text in.
 *
 * @return string|bool The flavor text, or false if there was an error.
 */
function get_flavor_text_in_language( string $move_url, string $language ) {

	$move_data = fetch_api_data( $move_url );
	if ( ! $move_data ) {
		return false;
	}

	foreach ( $move_data['flavor_text_entries'] as $entry ) {
		if ( $entry['language']['name'] === $language ) {
			return str_replace( "\n", '', $entry['flavor_text'] );
		}
	}

	return false;
}

/**
 * Get data from the PokeAPI.
 *
 * @param string $url The endpoint to get data from.
 *
 * @return array|bool The data from the API, or false if there was an error.
 */
function fetch_api_data( string $url ) {
	$api_data_response = wp_remote_get( $url );
	if ( is_wp_error( $api_data_response ) ) {
		return false;
	}

	$api_data = json_decode( wp_remote_retrieve_body( $api_data_response ), true );
	if ( ! empty( $api_data ) ) {
		return $api_data;
	}

	return false;
}

