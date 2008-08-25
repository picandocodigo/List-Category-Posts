<?php
/*
Plugin Name: List category posts
Plugin URI: http://picandocodigo.net/programacion/wordpress/list-category-posts-wordpress-plugin-english/
Description: List Category Posts allows you to list posts from a category into a post/page using the [catlist] shortcode. This shortcode accepts a category name or id, the order in which you want the posts to display, and the number of posts to display. You can use [catlist] as many times as needed with different arguments. Usage: [catlist argument1=value1 argument2=value2].
Version: 0.3
Author: Fernando Briano
Author URI: http://picandocodigo.net/wordpress/
*/

/* Copyright 2008  Fernando Briano  (email : transformers.es@gmail.com)

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

//Shortcode [catlist parameter="value"]
function catlist_func($atts, $content=null) {
	$atts=shortcode_atts(array(
		'id' => '0',
		'name' => 'default',
		'orderby' => 'date',
		'order' => 'desc',
		'numberposts' => '5'
	), $atts);
	return list_category_posts($atts);
}
add_shortcode('catlist', 'catlist_func');

function list_category_posts($atts){
	$output = "<ul class='lcp_catlist'>";
	if($atts['name']!='default' && $atts['id']!='0'){
		$category='category_name='.$atts['name'];
	}else{
		$category='category='.$atts['id'];
	}
	$catposts=get_posts($category.'&numberposts='.$atts['numberposts'].'&orderby='.$atts['orderby'].'&order='.$atts['order']);
	foreach($catposts as $single):
		$output .= "<li><a href='".get_permalink($single->ID)."'>".$single->post_title."</a></li>";
	endforeach;
	$output .= "</ul>";
	return $output;
}

function lcp_add_option_page(){
	add_options_page('List Category Posts', 'List Category Posts', 'manage_options','list-category-posts/list_cat_posts_options.php');
}

//Sidebar Widget:
include('list_cat_posts_widget.php');
//Filters and actions:
add_action('plugins_loaded', 'lcp_load_widget');

?>
