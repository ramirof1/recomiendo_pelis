<?php


function theme_enqueue_styles() {

    wp_enqueue_style('marvel', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('custom', get_stylesheet_directory_uri() . '/style.css' , array( 'marvel' ) );

}
add_action( 'wp_enqueue_scripts' , 'theme_enqueue_styles' , PHP_INT_MAX);

function foundation_enqueue_styles() {

    wp_enqueue_style('foundation', get_template_directory_uri() . 'css/foundation.css');
    wp_enqueue_style('customfoundation', get_stylesheet_directory_uri() . '/jquery-ui.css' , array( 'foundation' ) );

}

add_action( 'wp_enqueue_scripts' , 'foundation_enqueue_styles' , PHP_INT_MAX);

add_action('pre_get_posts', 'wpsites_exclude_latest_post');
/**
 * @author    Brad Dalton
 * @example   http://wpsites.net/wordpress-tips/exclude-latest-post-from-wordpress-home-page-loop/
 * @copyright 2014 WP Sites
 */
function wpsites_exclude_latest_post($query) {
if ($query->is_home() && $query->is_main_query()) {
$query->set( 'offset', '1' );
	}
}


function new_wp_trim_excerpt($text) {
  $raw_excerpt = $text;
  if ( '' == $text ) {
    $text = get_the_content('');
    $text = strip_shortcodes( $text );
    $text = apply_filters('the_content', $text);
    $text = str_replace(']]>', ']]>', $text);
    $text = strip_tags($text, '<a>');
    $excerpt_length = apply_filters('excerpt_length', 55);
    $excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
    $words = preg_split('/(<a.*?a>)|\n|\r|\t|\s/', $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE );
    if ( count($words) > $excerpt_length ) {
      array_pop($words);
      $text = implode(' ', $words);
      $text = $text . $excerpt_more;
      }
    else {
      $text = implode(' ', $words);
      }
    }
  return apply_filters('new_wp_trim_excerpt', $text, $raw_excerpt);
  }
remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'new_wp_trim_excerpt');
