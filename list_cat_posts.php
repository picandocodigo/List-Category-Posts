<?php
/*
  Plugin Name: List category posts
  Plugin URI: https://github.com/picandocodigo/List-Category-Posts
  Description: List Category Posts allows you to list posts by category in a post/page using the [catlist] shortcode. This shortcode accepts a category name or id, the order in which you want the posts to display, the number of posts to display and many more parameters. You can use [catlist] as many times as needed with different arguments. Usage: [catlist argument1=value1 argument2=value2].
  Version: 0.62
  Author: Fernando Briano
  Author URI: http://fernandobriano.com

  Text Domain:   list-category-posts
  Domain Path:   /languages/

  Copyright 2008-2015  Fernando Briano  (email : fernando@picandocodigo.net)

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
  /**
   * Gets the shortcode parameters and instantiate plugin objects
   * @param $atts
   * @param $content
   */
  static function catlist_func($atts, $content = null) {
    $atts = shortcode_atts(array(
                             'id' => '0',
                             'name' => '',
                             'orderby' => 'date',
                             'order' => 'desc',
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
                             'comments' => 'no',
                             'comments_tag' => '',
                             'comments_class' => '',
                             'starting_with' => '',
                             'thumbnail' => 'no',
                             'thumbnail_size' => 'thumbnail',
                             'thumbnail_class' => '',
                             'title_tag' => '',
                             'title_class' => '',
                             'title_limit' => '0',
                             'post_type' => '',
                             'post_status' => '',
                             'post_parent' => '0',
                             'post_suffix' => '',
                             'show_protected' => 'no',
                             'class' => 'lcp_catlist',
                             'customfield_name' => '',
                             'customfield_value' =>'',
                             'customfield_display' =>'',
                             'customfield_display_name' =>'',
                             'customfield_orderby' =>'',
                             'customfield_tag' => '',
                             'customfield_class' => '',
                             'taxonomy' => '',
                             'categorypage' => '',
                             'category_count' => '',
                             'morelink' => '',
                             'morelink_class' => '',
                             'morelink_tag' => '',
                             'posts_morelink' => '',
                             'posts_morelink_class' => '',
                             'year' => '',
                             'monthnum' => '',
                             'search' => '',
                             'link_target' => '',
                             'pagination' => 'no',
                             'pagination_next' => '>>',
                             'pagination_prev' => '<<',
                             'no_posts_text' => "",
                             'instance' => '0',
                             'no_post_titles' => 'no',
                             'link_titles' => true
                           ), $atts);
    if( $atts['numberposts'] == ''){
      $atts['numberposts'] = get_option('numberposts');
    }
    if( $atts['pagination'] == 'yes'){
      lcp_pagination_css();
    }
    $catlist_displayer = new CatListDisplayer($atts);
    return $catlist_displayer->display();
  }
}

add_shortcode( 'catlist', array('ListCategoryPosts', 'catlist_func') );

function lpc_meta($links, $file) {
  $plugin = plugin_basename(__FILE__);

  if ($file == $plugin):
    return array_merge(
      $links,
      array( sprintf('<a href="http://wordpress.org/extend/plugins/list-category-posts/other_notes/">%s</a>', __('How to use','list-category-posts')) ),
      array( sprintf('<a href="http://picandocodigo.net/programacion/wordpress/list-category-posts-wordpress-plugin-english/#support">%s</a>', __('Donate','list-category-posts')) ),
      array( sprintf('<a href="https://github.com/picandocodigo/List-Category-Posts">%s</a>', __('Fork on Github','list-category-posts')) )
    );
  endif;

  return $links;
}

add_filter( 'plugin_row_meta', 'lpc_meta', 10, 2 );

function load_i18n(){
  load_plugin_textdomain( 'list-category-posts', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'load_i18n' );

function lcp_pagination_css(){
  if ( @file_exists( get_stylesheet_directory() . '/lcp_paginator.css' ) ):
    $css_file = get_stylesheet_directory_uri() . '/lcp_paginator.css';
  elseif ( @file_exists( get_template_directory() . '/lcp_paginator.css' ) ):
    $css_file = get_template_directory_uri() . '/lcp_paginator.css';
  else:
    $css_file = plugin_dir_url(__FILE__) . '/lcp_paginator.css';
  endif;

  wp_enqueue_style( 'lcp_paginator', $css_file);
}

/**
 * TO-DO:
- Pagination * DONE - Need to add "page" text
- Add Older Posts at bottom of List Category Post page
- Simpler template system
- Exclude child categories
 */
