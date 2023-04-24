<?php
/**
 * Responsible for creating a REST endpoint to fetch a Pokemon's data.
 *
 * @package pokestrap
 */

namespace pokestrap\theme;

add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'pokemon/v1',
			'/data/(?P<id>\d+)',
			array(
				'methods'  => 'GET',
				'callback' => 'pokestrap\theme\get_pokemon_data',
			)
		);
	}
);


function get_pokemon_data( $data ) {
	$post_id = $data['id'];
	$post    = get_post( $post_id );

	$pokemon_data = array(
		'photo'       => get_the_post_thumbnail_url( $post_id ),
		'name'        => $post->post_title,
		'description' => $post->post_content,
		'types'       => array(),
		'weight'      => get_post_meta( $post_id, 'pokemon_weight', true ),
		'index_new'   => get_post_meta( $post_id, 'pokemon_index_new', true ),
		'index_old'   => get_post_meta( $post_id, 'pokemon_index_old', true ),
		'attacks'     => array(),
	);

	$pokemon_types = get_the_terms( $post_id, 'type' );
	if ( $pokemon_types && ! is_wp_error( $pokemon_types ) ) {
		foreach ( $pokemon_types as $pokemon_type ) {
			$pokemon_type_data       = array(
				'name' => $pokemon_type->name,
			);
			$pokemon_data['types'][] = $pokemon_type_data;
		}
	}

	$pokemon_attacks = get_the_terms( $post_id, 'attack' );
	if ( $pokemon_attacks && ! is_wp_error( $pokemon_attacks ) ) {
		foreach ( $pokemon_attacks as $pokemon_attack ) {
			$pokemon_attack_data       = array(
				'name'        => $pokemon_attack->name,
				'description' => $pokemon_attack->description,
			);
			$pokemon_data['attacks'][] = $pokemon_attack_data;
		}
	}

	return $pokemon_data;
}
