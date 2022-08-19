<?php
/*
  Plugin Name: List category posts
  Plugin URI: https://github.com/picandocodigo/List-Category-Posts
  Description: List Category Posts allows you to list posts by category in a post/page using the [catlist] shortcode. This shortcode accepts a category name or id, the order in which you want the posts to display, the number of posts to display and many more parameters. You can use [catlist] as many times as needed with different arguments. Usage: [catlist argument1=value1 argument2=value2].
  Version: 0.87
  Author: Fernando Briano
  Author URI: http://fernandobriano.com

  Text Domain:   list-category-posts
  Domain Path:   /languages/

  Copyright 2008-2020  Fernando Briano  (email : fernando@picandocodigo.net)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 3 of the License, or
  any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


include 'include/lcp-widget.php';
include 'include/lcp-options.php';
require_once 'include/lcp-catlistdisplayer.php';

class ListCategoryPosts{
  private static $default_params = null;

  public static function default_params(){
    if (self::$default_params === null) {
      self::$default_params = array(
        'id' => '0',
        'name' => '',
        'orderby' => '',
        'order' => '',
        'numberposts' => '',
        'date' => 'no',
        'date_tag' => '',
        'date_class' =>'',
        'dateformat' => get_option('date_format'),
        'date_modified' => '',
        'date_modified_tag' => '',
        'date_modified_class' => '',
        'author' => 'no',
        'author_posts_link' => 'no',
        'author_tag' =>'',
        'author_class' => '',
        'author_posts' => '',
        'template' => '',
        'excerpt' => 'no',
        'excerpt_size' => '55',
        'excerpt_strip' => 'yes',
        'excerpt_overwrite' => 'no',
        'excerpt_tag' =>'',
        'excerpt_class' =>'',
        'exclude' => '0',
        'excludeposts' => '0',
        'includeposts' => '0',
        'offset' => '0',
        'tags' => '',
        'exclude_tags' => '',
        'currenttags' => '',
        'content' => 'no',
        'content_tag' => '',
        'content_class' => '',
        'display_id' => 'no',
        'catlink' => 'no',
        'catname' => 'no',
        'catlink_string' => '',
        'catlink_tag' =>'',
        'catlink_class' => '',
        'posts_tags' => '',
        'posts_tags_tag' => '',
        'posts_tags_class' => '',
        'posts_tags_prefix' => ' ',
        'posts_tags_glue' => ', ',
        'posts_tags_inner' => '',
        'posts_taglink' => 'no',
        'posts_cats' => '',
        'posts_cats_tag' => '',
        'posts_cats_class' => '',
        'posts_cats_prefix' => ' ',
        'posts_cats_glue' => ', ',
        'posts_cats_inner' => '',
        'posts_catlink' => '',
        'child_categories' => 'yes',
        'comments' => 'no',
        'comments_tag' => '',
        'comments_class' => '',
        'starting_with' => '',
        'thumbnail' => 'no',
        'thumbnail_size' => 'thumbnail',
        'thumbnail_tag' => '',
        'thumbnail_class' => '',
        'force_thumbnail' => '',
        'title_tag' => '',
        'title_class' => '',
        'title_limit' => '0',
        'post_type' => '',
        'post_status' => '',
        'post_parent' => '',
        'post_suffix' => '',
        'show_protected' => 'no',
        'class' => '',
        'conditional_title' => '',
        'conditional_title_tag' => '',
        'conditional_title_class' => '',
        'customfield_name' => '',
        'customfield_value' =>'',
        'customfield_display' =>'',
        'customfield_display_glue' => '',
        'customfield_display_name' =>'',
        'customfield_display_name_glue' => ' : ',
        'customfield_display_separately' => 'no',
        'customfield_orderby' => '',
        'customfield_orderby_type' => '',
        'customfield_tag' => '',
        'customfield_class' => '',
        'customfield_compare' => '',
        'taxonomy' => '',
        'taxonomies_and' => '',
        'taxonomies_or' => '',
        'terms' => '',
        'categorypage' => '',
        'category_count' => '',
        'category_description' => 'no',
        'category_description_tag' => '',
        'category_description_class' => '',
        'morelink' => '',
        'morelink_class' => '',
        'morelink_tag' => '',
        'posts_morelink' => '',
        'posts_morelink_class' => '',
        'year' => '',
        'monthnum' => '',
        'search' => '',
        'link_target' => '',
        'pagination' => '',
        'pagination_next' => '>>',
        'pagination_prev' => '<<',
        'pagination_padding' => '5',
        'no_posts_text' => "",
        'instance' => '0',
        'no_post_titles' => 'no',
        'link_titles' => true,
        'link_current' => '',
        'link_dates' => 'no',
        'after' => '',
        'after_year' => '',
        'after_month' => '',
        'after_day' => '',
        'before' => '',
        'before_year' => '',
        'before_month' => '',
        'before_day' => '',
        'tags_as_class' => 'no',
        'pagination_bookmarks' => '',
        'ol_offset' => '',
        'main_query' => '',
        'keep_orderby_filters' => '',
        'ignore_sticky_posts' => '',
        'cat_sticky_posts' => '',
      );
    }
    return self::$default_params;
  }

  /**
   * Gets the shortcode parameters and instantiate plugin objects
   * @param $atts
   * @param $content
   */
  static function catlist_func($atts) {
    // Can be filtered using the shortcode_atts_catlist hook.
    $atts = shortcode_atts(self::default_params(), $atts, 'catlist');

    if($atts['numberposts'] == ''){
      $atts['numberposts'] = get_option('numberposts');
    }
    if($atts['pagination'] == 'yes' ||
       (get_option('lcp_pagination') === 'true' &&
        $atts['pagination'] !== 'false') ){
      lcp_pagination_css();
    }
    $catlist_displayer = new CatListDisplayer($atts);
    return $catlist_displayer->display();
  }
}

add_shortcode( 'catlist', array('ListCategoryPosts', 'catlist_func') );

function lpc_meta($links, $file) {
  $plugin = plugin_basename(__FILE__);

  if ($file == $plugin) {
    return array_merge(
      $links,
      array( sprintf('<a href="http://wordpress.org/extend/plugins/list-category-posts/other_notes/">%s</a>', __('How to use','list-category-posts')) ),
      array( sprintf('<a href="http://picandocodigo.net/programacion/wordpress/list-category-posts-wordpress-plugin-english/#support">%s</a>', __('Donate','list-category-posts')) ),
      array( sprintf('<a href="https://github.com/picandocodigo/List-Category-Posts">%s</a>', __('Fork on Github','list-category-posts')) )
    );
  }

  return $links;
}

add_filter( 'plugin_row_meta', 'lpc_meta', 10, 2 );

//adds a default value to numberposts on plugin activation
function set_default_numberposts() {
    add_option('numberposts', 10);
}
register_activation_hook( __FILE__, 'set_default_numberposts' );

function load_i18n(){
  load_plugin_textdomain(
    'list-category-posts',
    false,
    dirname( plugin_basename( __FILE__ ) ) . '/languages/'
  );
}
add_action( 'plugins_loaded', 'load_i18n' );

function lcp_pagination_css(){
  if ( @file_exists( get_stylesheet_directory() . '/lcp_paginator.css' ) ) {
    $css_file = get_stylesheet_directory_uri() . '/lcp_paginator.css';
  } elseif ( @file_exists( get_template_directory() . '/lcp_paginator.css' ) ) {
    $css_file = get_template_directory_uri() . '/lcp_paginator.css';
  } else {
    $css_file = plugin_dir_url(__FILE__) . '/lcp_paginator.css';
  }

  wp_enqueue_style( 'lcp_paginator', $css_file);
}

/**
 * TO-DO:
- Add Older Posts at bottom of List Category Post page
- Simpler template system
- Exclude child categories
 */
