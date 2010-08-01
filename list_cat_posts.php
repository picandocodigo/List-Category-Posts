<?php
/*
Plugin Name: List category posts
Plugin URI: http://picandocodigo.net/programacion/wordpress/list-category-posts-wordpress-plugin-english/
Description: List Category Posts allows you to list posts from a category into a post/page using the [catlist] shortcode. This shortcode accepts a category name or id, the order in which you want the posts to display, and the number of posts to display. You can use [catlist] as many times as needed with different arguments. Usage: [catlist argument1=value1 argument2=value2].
Version: 0.12
Author: Fernando Briano
Author URI: http://picandocodigo.net/
*/

/* Copyright 2008-2010  Fernando Briano  (email : fernando@picandocodigo.net)

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

//Sidebar Widget:
include('list_cat_posts_widget.php');

//Shortcode [catlist parameter="value"]
function catlist_func($atts, $content=null) {
	$atts=shortcode_atts(array(
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
			'comments' => 'no'
		), $atts);
	return list_category_posts($atts);
}

add_shortcode('catlist', 'catlist_func');

function list_category_posts($atts){
	if($atts['name']!='default' && $atts['id']=='0'){
		$category = 'category_name=' . $atts['name'];
		$category_id = get_cat_ID($atts['name']);
	}else{
		$category = 'cat=' . $atts['id'];
		$category_id = $atts['id'];
	}

	//Link to the category:
	$cat_link_string = '';
	if ($atts['catlink'] == 'yes'){
		$cat_link = get_category_link($category_id);
		$cat_data = get_category($category_id);
		$cat_title = $cat_data->name;
		$cat_link_string = '<a href=' . $cat_link . ' title="' . $cat_title . '">' . $cat_title . '</a>';
	}
	//Build the query for get_posts()
	$catposts = get_posts($category.'&numberposts=' . $atts['numberposts'] .
				'&orderby=' . $atts['orderby'] .
				'&order=' . $atts['order'] .
				'&exclude=' . $atts['excludeposts'] .
				'&tag=' . $atts['tags'] .
				'&offset=' . $atts['offset'] );
	//Template code:
	$tplFileName = null;
	$possibleTemplates = array(
		// File locations lower in list override others
		STYLESHEETPATH.'/list-category-posts/'.$atts['template'].'.php',
	);
	foreach($possibleTemplates as $key => $file) {
		if (is_readable($file)) {
			$tplFileName = $file;
		}
	}
	if ((!empty($tplFileName)) && (is_readable($tplFileName))) {
		require($tplFileName);
	}else{
		if ($cat_link_string != ''){
			$lcp_output = '<p><strong>' . $cat_link_string . '</strong></p>';
		}else{
			$lcp_output = '';
		}
		$lcp_output .= '<ul class="lcp_catlist">';//For default ul
		foreach($catposts as $single):
			$lcp_output .= '<li><a href="' . get_permalink($single->ID).'">' . $single->post_title . '</a>';
			if($atts['comments'] == yes){
				$lcp_output .= ' (' . $single->comment_count . ')';
			}
			if($atts['date']=='yes'){
				$lcp_output .=  ' - ' . get_the_time($atts['dateformat'], $single);//by Verex, great idea!
			}
			if($atts['author']=='yes'){
				$lcp_userdata = get_userdata($single->post_author);
				$lcp_output.=" - ".$lcp_userdata->display_name . '<br/>';
			}
			if($atts['content']=='yes' && $single->post_content){
				$lcp_output.= lcp_content($single); // line tweaked to output filtered content
			}
			if($atts['excerpt']!='no' && !($atts['content']=='yes' && $single->post_content) ){
				$lcp_output .= lcp_excerpt($single);
			}
			$lcp_output.="</li>";
		endforeach;
		$lcp_output .= "</ul>";
	}
	return $lcp_output;
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

/** TODO - These are the todo's for a 1.0 release:
 *  -Images (preview or thumbnail, whatever, I have to dig into this)
 *  -Pagination
 *  -Simplify template system
 *  -i18n
 */
?>
