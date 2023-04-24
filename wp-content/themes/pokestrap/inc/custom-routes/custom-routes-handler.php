<?php
/**
 * Responsible for handling the custom routes.
 *
 * @package pokestrap
 */

namespace pokestrap\theme;

/**
 * Responsible for adding the custom routes.
 *
 * @return void
 */
function add_custom_rewrite_rules() {
	add_rewrite_rule( 'generate/?', 'index.php?custom_route=generate', 'top' );
	add_rewrite_rule( 'random/?', 'index.php?custom_route=random', 'top' );
}

add_action( 'init', 'pokestrap\theme\add_custom_rewrite_rules', PHP_INT_MAX );

/**
 * Responsible for handling the custom routes.
 *
 * @return void
 */
function custom_routes_handler() {

	$custom_route = get_query_var( 'custom_route' );

	if ( null === $custom_route ) {
		return;
	}

	switch ( $custom_route ) {
		case 'generate':
			require_once get_stylesheet_directory() . '/inc/custom-routes/generate-pokemon-from-api.php';
			exit;
		case 'random':
			require_once get_stylesheet_directory() . '/inc/custom-routes/redirect-to-random-pokemon.php';
			exit;
	}
}

add_action( 'template_redirect', 'pokestrap\theme\custom_routes_handler' );

// Whitelist the custom route query var.
add_filter(
	'query_vars',
	function( $query_vars ) {
		$query_vars[] = 'custom_route';
		return $query_vars;
	}
);
