<?php
/*
Plugin Name: List category posts
Plugin URI: http://picandocodigo.net/programacion/wordpress/list-category-posts-wordpress-plugin-english/
Description: List Category Posts allows you to list posts from a category into a post/page using the [catlist] shortcode. This shortcode accepts a category name or id, the order in which you want the posts to display, and the number of posts to display. You can use [catlist] as many times as needed with different arguments. Usage: [catlist argument1=value1 argument2=value2].
Version: 0.16
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


include('list_cat_posts_widget.php');

/**
 * 
 * Main plugin function: Gets the shortcode parameters, set defaults, and call the plugin's function. 
 * @param $atts
 * @param $content
 */
function catlist_func($atts, $content = null) {
	$atts = shortcode_atts(array(
			'id' => '0',
			'name' => 'default',
			'orderby' => 'date',
			'order' => 'desc',
			'numberposts' => '5',
			'date' => 'no',
			'author' => 'no',
			'dateformat' => get_option('date_format'),
			'template' => 'default',
			'excerpt' => 'no',
			'exclude' => '0',
			'excludeposts' => '0',
			'offset' => '0',
			'tags' => '',
			'content' => 'no',
			'catlink' => 'no',
			'comments' => 'no',
			'thumbnail' => 'no',
			'post_type' => '',
			'post_parent' => '0',
			'class' => 'lcp_catlist',
			'customfield_name' => '',
			'customfield_value' =>'',
			'customfield_display' =>''
		), $atts);
	return list_category_posts($atts);
}

/* Add the shortcode to WordPress */
add_shortcode('catlist', 'catlist_func');

/**
 * Main function, this is where the flow goes and calls auxiliary functions 
 * @param array $atts
 */
function list_category_posts($atts){
	$lcp_category_id = $atts['id'];	
	$lcp_category_name = $atts['name'];
	
	//Get the category posts:
	$catposts = lcp_category($lcp_category_id, $lcp_category_name, $atts);
	
	$lcp_output = '';
	
	//Link to the category:
	if ($atts['catlink'] == 'yes'){
		$cat_link = get_category_link($lcp_category_id);
		$cat_title = get_cat_name($lcp_category_id);
		$lcp_output .= '<a href="' . $cat_link . '" title="' . $cat_title . '">' . $cat_title . '</a>';
	}
	
	//Template code:
	$tplFileName = null;
	$possibleTemplates = array(
		// File locations lower in list override others
		STYLESHEETPATH.'/list-category-posts/'.$atts['template'].'.php',
	);
	foreach ($possibleTemplates as $key => $file) {
		if (is_readable($file)) {
			$tplFileName = $file;
		}
	}
	if ((!empty($tplFileName)) && (is_readable($tplFileName))) {
		require($tplFileName);
	}else{
		// Default template
		$lcp_output .= '<ul class="'.$atts['class'].'">';
		
		foreach ($catposts as $single):
			$lcp_output .= lcp_display_post($single, $atts);
		endforeach;
		
		$lcp_output .= "</ul>";
	}
	return $lcp_output;
}

/**
 * Get the categories & posts
 * @param string $lcp_category_id
 * @param string $lcp_category_name
 */
function lcp_category($lcp_category_id, $lcp_category_name, $atts){
	if($lcp_category_name != 'default' && $lcp_category_id == '0'){
		$lcp_category = 'category_name=' . $atts['name'];
	}else{
		$lcp_category = 'cat=' . $lcp_category_id;
	}

	//Build the query for get_posts()
	$lcp_query = $lcp_category.'&numberposts=' . $atts['numberposts'] .
				'&orderby=' . $atts['orderby'] .
				'&order=' . $atts['order'] .
				'&exclude=' . $atts['excludeposts'] .
				'&tag=' . $atts['tags'] .
				'&offset=' . $atts['offset'];
	
	// Post type and post parent:
	if($atts['post_type']): $lcp_query .= '&post_type=' . $atts['post_type']; endif;
	if($atts['post_parent']): $lcp_query .= '&post_parent=' . $atts['post_parent']; endif;

	// Custom fields 'customfield_name' & 'customfield_value' should both be defined
	if($atts['customfield_name']!='' && $atts['customfield_value'] != ''):
		$lcp_query .= '&meta_key=' . $atts['customfield_name'] . '&meta_value=' . $atts['customfield_value'];
	endif;
	
	return get_posts($lcp_query);
}

function lcp_display_post($single, $atts){
	$lcp_display_output = '<li><a href="' . get_permalink($single->ID).'">' . $single->post_title . '</a>';
	
	if ($atts['comments'] == yes){
		$lcp_display_output .= ' (';
		$lcp_display_output .=  lcp_comments($single);
		$lcp_display_output .=  ')';
	}
	
	if ($atts['date']=='yes'){
		$lcp_display_output .= lcp_showdate($single, $atts['dateformat']);
	}
	
	if ($atts['author']=='yes'){
		$lcp_display_output .= " - ".lcp_showauthor($single) . '<br/>';
	}
	
	if ($atts['content']=='yes' && $single->post_content){
		$lcp_display_output.= lcp_content($single); // line tweaked to output filtered content
	}
	
	if ($atts['excerpt']!='no' && !($atts['content']=='yes' && $single->post_content) ){
		$lcp_display_output .= lcp_excerpt($single);
	}
	
	if ($atts['thumbnail']=='yes'){
		$lcp_display_output .= lcp_thumbnail($single);
	}
	
	if($atts['customfield_display'] != ''){
		$lcp_display_output .= lcp_display_customfields();
	}
	$lcp_display_output.="</li>";
	return $lcp_display_output;
}

function lcp_comments($single){
	return $single->comment_count;
}

function lcp_showauthor($single){
	$lcp_userdata = get_userdata($single->post_author);
	return $lcp_userdata->display_name;
}

function lcp_showdate($single, $dateformat){
	return  ' - ' . get_the_time($dateformat, $single);//by Verex, great idea!
}

function lcp_content($single){
	$lcp_content = apply_filters('the_content', $single->post_content); // added to parse shortcodes
	$lcp_content = str_replace(']]>', ']]&gt', $lcp_content); // added to parse shortcodes
	return '<p>' . $lcp_content . '</p>';
}


function lcp_excerpt($single){
	if($single->post_excerpt){
		return '<p>' . $single->post_excerpt . '</p>';
	}
	$lcp_excerpt = strip_tags($single->post_content);
	if ( post_password_required($post) ) {
		$lcp_excerpt = __('There is no excerpt because this is a protected post.');
		return $lcp_excerpt;
	}
	if (strlen($lcp_excerpt) > 255) {
		$lcp_excerpt = substr($lcp_excerpt, 0, 252) . '...';
	}
	return '<p>' . $lcp_excerpt . '</p>';
}


function lcp_thumbnail($single){
	$lcp_thumbnail = '';
	if ( has_post_thumbnail($single->ID) ) {
		$lcp_thumbnail = get_the_post_thumbnail($single->ID);
	}
	return $lcp_thumbnail;
}

function lcp_display_customfields($custom_key = array(), $post_id){
	foreach ($custom_key as $c) {
		/**
		 * TODO - Display custom fields
		 */
		get_post_custom_values($c, $post_id);
	}
}

/** TODO - These are the todo's for a 1.0 release:
 *  -Pagination
 *  -Simplify template system
 *  -i18n
 */

/**
 *  TODO: Fix the thing about many categories (not working now...) 
 */
?>
