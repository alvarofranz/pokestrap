<?php
/**
 * Responsible for redirecting to a random Pokemon post.
 *
 * @package pokestrap
 */

// Get a random Pokemon post.
$random_pokemon_post = get_posts(
	array(
		'post_type'      => 'pokemon',
		'orderby'        => 'rand',
		'posts_per_page' => 1,
	)
);

// Make sure we have a post to redirect to.
if ( $random_pokemon_post[0] ) {
	wp_safe_redirect( get_permalink( $random_pokemon_post[0]->ID ) );
	exit;
} else {
	wp_die( 'An error occurred while trying to redirect to a random Pokemon.' );
}
