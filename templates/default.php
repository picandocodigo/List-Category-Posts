<?php
/*
Plugin Name: List Category Posts - Template "Default"
Plugin URI: http://picandocodigo.net/programacion/wordpress/list-category-posts-wordpress-plugin-english/
Description: Template file for List Category Post Plugin for Wordpress which is used by plugin by argument template=value.php
Version: 0.9
Author: Radek Uldrych & Fernando Briano
Author URI: http://picandocodigo.net http://radoviny.net
*/

/*
Copyright 2009 Radek Uldrych (email : verex@centrum.cz)
Copyright 2009-2015 Fernando Briano (http://picandocodigo.net)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or any
later version.

This program is distributed in the hope that it will be useful, but
WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301
USA
*/

/**
* The format for templates changed since version 0.17.  Since this
* code is included inside CatListDisplayer, $this refers to the
* instance of CatListDisplayer that called this file.
*/

/* This is the string which will gather all the information.*/
$lcp_display_output = '';

// Show category link:
$lcp_display_output .= $this->get_category_link('strong');

// Show category description:
$lcp_display_output .= $this->get_category_description('p');

// Show the conditional title:
$lcp_display_output .= $this->get_conditional_title();

//Add 'starting' tag. Here, I'm using an unordered list (ul) as an example:
$lcp_display_output .= $this->open_outer_tag('ul', 'lcp_catlist');

/* Posts Loop
 *
 * The code here will be executed for every post in the category.  As
 * you can see, the different options are being called from functions
 * on the $this variable which is a CatListDisplayer.
 *
 * CatListDisplayer has a function for each field we want to show.  So
 * you'll see get_excerpt, get_thumbnail, etc.  You can now pass an
 * html tag as a parameter. This tag will sorround the info you want
 * to display. You can also assign a specific CSS class to each field.
 *
 * IMPORTANT: Prior to v0.85 lines 65-67 were different. Make sure your
 * template is up to date.
*/
global $post;
while ( $this->lcp_query->have_posts() ):
  $this->lcp_query->the_post();

  // Check if protected post should be displayed
  if (!$this->check_show_protected($post)) continue;

  //Start a List Item for each post:
  $lcp_display_output .= $this->open_inner_tag($post, 'li');

  //Show the title and link to the post:
  $lcp_display_output .= $this->get_post_title($post);

  // Show categories
  $lcp_display_output .= $this->get_posts_cats($post);

  // Show tags
  $lcp_display_output .= $this->get_posts_tags($post);

  //Show comments:
  $lcp_display_output .= $this->get_comments($post);

  //Show date:
  $lcp_display_output .= $this->get_date($post);

  //Show date modified:
  $lcp_display_output .= $this->get_modified_date($post);

  //Show author
  $lcp_display_output .= $this->get_author($post);

  // Show post ID
  $lcp_display_output .= $this->get_display_id($post);

  //Custom fields:
  $lcp_display_output .= $this->get_custom_fields($post);

  //Post Thumbnail
  $lcp_display_output .= $this->get_thumbnail($post);

  /**
   * Post content - Example of how to use tag and class parameters:
   * This will produce:<div class="lcp_content">The content</div>
   */
  $lcp_display_output .= $this->get_content($post, 'div', 'lcp_content');

  /**
   * Post content - Example of how to use tag and class parameters:
   * This will produce:<div class="lcp_excerpt">The content</div>
   */
  $lcp_display_output .= $this->get_excerpt($post, 'div', 'lcp_excerpt');

  // Get Posts "More" link:
  $lcp_display_output .= $this->get_posts_morelink($post);

  //Close li tag
  $lcp_display_output .= $this->close_inner_tag();
endwhile;

// Show no posts text if there are no posts
$lcp_display_output .= $this->get_no_posts_text();

// Close the wrapper I opened at the beginning:
$lcp_display_output .= $this->close_outer_tag();

// If there's a "more link", show it:
$lcp_display_output .= $this->get_morelink();

// Get category posts count
$lcp_display_output .= $this->get_category_count();

//Pagination
$lcp_display_output .= $this->get_pagination();

$this->lcp_output = $lcp_display_output;
