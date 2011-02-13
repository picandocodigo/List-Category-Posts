<?php
/*
Plugin Name: List Category Posts - Template
Plugin URI: http://picandocodigo.net/programacion/wordpress/list-category-posts-wordpress-plugin-english/
Description: Template file for List Category Post Plugin for Wordpress which is used by plugin by argument template=value.php
Version: 0.8
Author: Radek Uldrych & Fernando Briano 
Author URI: http://picandocodigo.net http://radoviny.net
*/

/* Copyright 2009  Radek Uldrych  (email : verex@centrum.cz), Fernando Briano (http://picandocodigo.net)

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

/**
 * The format for templates changed since version 0.17.
 * Since this code is included inside CatListDisplayer, $this refers to
 * the instance of CatListDisplayer that called this file.
 */

/* This is the string which will gather all the information.
 * We're starting it  */
$lcp_display_output = '';

//Add 'starting' tag. Here, I'm using an unordered list (ul) as an example:
$lcp_output .= '<ul class="lcp_catlist">';

/**
 * Posts loop.
 * The code here will be executed for every post in the category.
 * As you can see, the different options are being called from functions on the
 * $this variable which is a CatListDisplayer. The CatListDisplayer
 */
foreach ($this->catlist->get_categories_posts() as $single):
    //Start a List Item for each post:
    $lcp_display_output .= "<li>";

    //Show the title and link to the post:
    $lcp_display_output .= $this->get_post_title($single);

    //Show comments:
    $lcp_display_output .= $this->get_comments($single);

    //Show date:
    $lcp_display_output .= ' ' . $this->get_date($single);

    //Show author
    $lcp_display_output .= '<br/>' . __('Author') . ': ' . $this->get_author($single) . '<br/>';

    //Custom fields:
    $lcp_display_output .= $this->get_custom_fields($this->params['customfield_display'], $single->ID);

    //Post Thumbnail
    $lcp_display_output .= $this->get_thumbnail($single);

    //Post content
    $lcp_display_output .= $this->get_content($single);

    //Post excerpt
    $lcp_display_output .= $this->get_excerpt($single);

    //Close li tag
    $lcp_display_output .= '</li>';
endforeach;

$lcp_display_output .= '</ul>';
$this->lcp_output = $lcp_display_output;

?> 
