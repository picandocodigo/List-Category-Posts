<?php
/*
Plugin Name: List category posts
Plugin URI: http://picandocodigo.net/programacion/wordpress/list-category-posts-wordpress-plugin-english/
Description: List Category Posts allows you to list posts from a category into a post/page using the [catlist] shortcode. This shortcode accepts a category name or id, the order in which you want the posts to display, and the number of posts to display. You can use [catlist] as many times as needed with different arguments. Usage: [catlist argument1=value1 argument2=value2].
Version: 0.24
Author: Fernando Briano
Author URI: http://picandocodigo.net/
*/

/* Copyright 2008-2011  Fernando Briano  (email : fernando@picandocodigo.net)

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
                            'excerpt_size' => '255',
                            'excerpt_tag' =>'',
                            'excerpt_class' =>'',
                            'exclude' => '0',
                            'excludeposts' => '0',
                                'exclude_singular' => '0', // New
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
                            'post_parent' => '0',
                            'class' => 'lcp_catlist',
                            'customfield_name' => '',
                            'customfield_value' =>'',
                            'customfield_display' =>'',
                            'taxonomy' => '',
                            'categorypage' => '',
                            'morelink' => '',
                            'morelink_class' => ''
                    ), $atts);
            
            // New
            if ( !empty($attrs['exclude_singular']) && !empty($this->singular_id) ) {
                $attrs['excludeposts'] = ($attrs['excludeposts'] != '0')
                    ? $attrs['excludeposts'] . ',' . $this->singular_id
                    : $this->singular_id;
            }
            unset($attrs['exclude_singular']);
            
            $catlist_displayer = new CatListDisplayer($atts);
            return $catlist_displayer->display();
    }
    
    // New
    function action_pre_get_posts($query) {
        if ( $query->is_main_query() && $query->is_singular() ) {
            $this->singular_id = $query->get_queried_object_id();
        }
    }

}

$list_category_posts = new ListCategoryPosts();

add_action( 'pre_get_posts', array($list_category_posts, 'action_pre_get_posts') );
add_shortcode( 'catlist', array($list_category_posts, 'catlist_func') );

/**
  Fork/Edit by kelleyvanevert:
  Answering http://wordpress.stackexchange.com/questions/44895/exclude-current-page-from-list-of-pages/44984,
   we found there is no way to exlcude the CURRENTLY DISPLAYED single post/page.
  The solution I propose should solve the problem, I think; but I HAVEN'T tested yet.
 */

/**
 * TO-DO:
Add Older Posts at bottom of List Category Post page
  http://wordpress.stackexchange.com/questions/26398/add-older-posts-at-bottom-of-list-category-post-page
Getting the “more” tag to work with plugin-list-category-post
  http://wordpress.stackexchange.com/questions/30376/getting-the-more-tag-to-work-with-plugin-list-category-post
- Fix the code for the WordPress Coding Standards: http://codex.wordpress.org/WordPress_Coding_Standards
- i18n
- Pagination
- Simpler template system
- Exclude child categories
 */
