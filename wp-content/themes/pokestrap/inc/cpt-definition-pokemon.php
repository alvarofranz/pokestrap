<?php

namespace pokestrap\theme;

/**
 * Responsible for registering the Pokemon custom post type.
 * @return void
 */
function register_pokemon_post_type(): void
{
    $labels = array(
        'name'               => 'Pokemon',
        'singular_name'      => 'Pokemon',
        'menu_name'          => 'Pokemon',
        'name_admin_bar'     => 'Pokemon',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Pokemon',
        'new_item'           => 'New Pokemon',
        'edit_item'          => 'Edit Pokemon',
        'view_item'          => 'View Pokemon',
        'all_items'          => 'All Pokemon',
        'search_items'       => 'Search Pokemon',
        'parent_item_colon'  => 'Parent Pokemon:',
        'not_found'          => 'No pokemon found.',
        'not_found_in_trash' => 'No pokemon found in Trash.'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'pokemon' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'thumbnail' )
    );

    register_post_type( 'pokemon', $args );
}
add_action( 'init', 'pokestrap\theme\register_pokemon_post_type' );
