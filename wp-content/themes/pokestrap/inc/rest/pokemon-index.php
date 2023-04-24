<?php
/**
 * Responsible for creating a REST endpoint to fetch the index of Pokemon with their corresponding pokedex index.
 *
 * @package pokestrap
 */

namespace pokestrap\theme;

/**
 * Responsible for retrieving the Pokemon index.
 */
function get_pokemon_index() {
	$args  = array(
		'post_type'      => 'pokemon',
		'meta_key'       => 'pokemon_index_new',
		'orderby'        => 'meta_value_num',
		'order'          => 'ASC',
		'posts_per_page' => -1,
		'fields'         => 'ids',
	);

	$pokemon_ids = get_posts( $args );

	$pokemon_index = array();
	foreach ( $pokemon_ids as $pokemon_id ) {
		$pokemon_index[] = array(
			'id'    => get_post_meta( $pokemon_id, 'pokemon_index_new', true ),
			'title' => get_the_title( $pokemon_id ),
		);
	}

	return $pokemon_index;
}

add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'pokemon/v1',
			'/index',
			array(
				'methods'  => 'GET',
				'callback' => 'pokestrap\theme\get_pokemon_index',
			)
		);
	}
);
