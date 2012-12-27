<?php
  /*
    Plugin Name: List category posts
    Plugin URI: https://github.com/picandocodigo/List-Category-Posts
    Description: List Category Posts allows you to list posts from a category into a post/page using the [catlist] shortcode. This shortcode accepts a category name or id, the order in which you want the posts to display, and the number of posts to display. You can use [catlist] as many times as needed with different arguments. Usage: [catlist argument1=value1 argument2=value2].
    Version: 0.27
    Author: Fernando Briano
    Author URI: http://picandocodigo.net/

    Text Domain:   list-category-posts
    Domain Path:   /languages/
  */

  /* Copyright 2008-2012  Fernando Briano  (email : fernando@picandocodigo.net)

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

load_plugin_textdomain( 'list-category-posts', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

include 'include/ListCategoryPostsWidget.php';
require_once 'include/CatListDisplayer.php';

class ListCategoryPosts{
  /**
   * Gets the shortcode parameters and instantiate plugin objects
   * @param $atts
   * @param $content
   */
  function catlist_func($atts, $content = null) {
    $atts = shortcode_atts(array(
                             'id' => '0',
                             'name' => '',
                             'orderby' => 'date',
                             'order' => 'desc',
                             'numberposts' => '5',
                             'date' => 'no',
                             'date_tag' => '',
                             'date_class' =>'',
                             'dateformat' => get_option('date_format'),
                             'author' => 'no',
                             'author_tag' =>'',
                             'author_class' => '',
                             'template' => 'default',
                             'excerpt' => 'no',
                             'excerpt_size' => '55',
                             'excerpt_tag' =>'',
                             'excerpt_class' =>'',
                             'exclude' => '0',
                             'excludeposts' => '0',
                             'offset' => '0',
                             'tags' => '',
                             'content' => 'no',
                             'content_tag' => '',
                             'content_class' => '',
                             'catlink' => 'no',
                             'catlink_string' => '',
                             'catlink_tag' =>'',
                             'catlink_class' => '',
                             'comments' => 'no',
                             'comments_tag' => '',
                             'comments_class' => '',
                             'thumbnail' => 'no',
                             'thumbnail_size' => 'thumbnail',
                             'thumbnail_class' => '',
                             'title_tag' => '',
                             'title_class' => '',
                             'post_type' => '',
                             'post_status' => '',
                             'post_parent' => '0',
                             'class' => 'lcp_catlist',
                             'customfield_name' => '',
                             'customfield_value' =>'',
                             'customfield_display' =>'',
                             'taxonomy' => '',
                             'categorypage' => '',
                             'morelink' => '',
                             'morelink_class' => '',
                             'year' => '',
                             'monthnum' => '',
                             'search' => ''
                           ), $atts);

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

/**
 * TO-DO:
Add Older Posts at bottom of List Category Post page
  http://wordpress.stackexchange.com/questions/26398/add-older-posts-at-bottom-of-list-category-post-page
Getting the “more” tag to work with plugin-list-category-post
  http://wordpress.stackexchange.com/questions/30376/getting-the-more-tag-to-work-with-plugin-list-category-post
- Fix the code for the WordPress Coding Standards: http://codex.wordpress.org/WordPress_Coding_Standards
- Pagination
- Simpler template system
- Exclude child categories
 */
