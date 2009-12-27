<?php
/*
Plugin Name: List Category Posts - Template
Plugin URI: http://picandocodigo.net/programacion/wordpress/list-category-posts-wordpress-plugin-english/
Description: Template file for List Category Post Plugin for Wordpress which is used by plugin by argument template=value.php
Version: 0.0.2
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
	$output .= '<p><strong>' . $cat_link_string . '</strong></p>';
	$output .= '<li><a href="' . get_permalink($single->ID) . '">' . $single->post_title . '</a>';
	//Style for date:
	if($atts['date']=='yes'){
		$output.= ' - ' . get_the_time($atts['dateformat'], $single);
	}
	//Show author?
	if($atts['author']=='yes'){
		$lcp_userdata = get_userdata($single->post_author);
		$output.=" - ".$lcp_userdata->user_nicename;
	}
	//Show content?
	if($atts['content']=='yes' && $single->post_content){
		$output .= "<p>$single->post_content</p>";
	}
	//Show excerpt?
	if($atts['excerpt']=='yes' && $single->post_excerpt && !($atts['content']=='yes' && $single->post_content) ){
		$output .= '<p>' . $single->post_excerpt . '</p>';
	}
	$output.='</li>';
?> 
