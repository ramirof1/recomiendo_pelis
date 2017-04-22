<?php
/*
Plugin Name: Movie Reviews
Plugin URI: https://wordpress.org/plugins/movie-reviews/
Description: Pone estrellitas automaticamente
Version: 0.1.0
Author: Yo
Author URI: http://jhehehjke.com
Text Domain: movie-reviesws
Domain Path: /languages
*/

add_action( 'init', 'br_post_type');
function br_post_type() {
	$labels = array(
		'name'               => _x( 'Movie Reviews', 'post type general name', 'movie-reviews' ),
		'singular_name'      => _x( 'Movie Review', 'post type singular name', 'movie-reviews' ),
		'menu_name'          => _x( 'Movie Reviews', 'admin menu', 'movie-reviews' ),
		'name_admin_bar'     => _x( 'Movie Review', 'add new on admin bar', 'movie-reviews' ),
		'add_new'            => _x( 'Add New', 'movie', 'movie-reviews' ),
		'add_new_item'       => __( 'Add New Review', 'movie-reviews' ),
		'new_item'           => __( 'New Movie', 'movie-reviews' ),
		'edit_item'          => __( 'Edit Movie', 'movie-reviews' ),
		'view_item'          => __( 'View Movie', 'movie-reviews' ),
		'all_items'          => __( 'All Movies', 'movie-reviews' ),
		'search_items'       => __( 'Search Movies', 'movie-reviews' ),
		'parent_item_colon'  => __( 'Parent Movies:', 'movie-reviews' ),
		'not_found'          => __( 'No reviews found.', 'movie-reviews' ),
		'not_found_in_trash' => __( 'No reviews found in Trash.', 'movie-reviews' )
	);

	$args = array(
		'labels'             => $labels,
                'description'        => __( 'Movie Reviews.', 'movie-reviews' ),
		'public'             => true,
		'rewrite'            => array( 'slug' => 'movie_review' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
		'menu_icon'          => 'dashicons-format-video'
	);

	register_post_type( 'movie-review', $args );
}

add_action ( 'pre_get_posts', 'movie_a_query' );
function movie_a_query( $query ) {
	if ( ! $query->is_main_query() )
    return $query;

		elseif ( !is_admin() ) {
	   $query->set( 'post_type', array ( 'post', 'movie-review' ) );
		}
}
