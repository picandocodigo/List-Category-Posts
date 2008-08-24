<?php
/*
Plugin Name: List category posts
Plugin URI: http://picandocodigo.net/programacion/wordpress/list-category-posts-wordpress-plugin-english/
Description: List Category Posts is a simple WordPress plugin which allows you to list some posts from a category into a post/page using [catlist=ID], where ID stands for the Category Id. You can list several categories on the same page/post. You can use [catlist=ID] as many times as needed with different Id’s. You may also define a limit of posts to show. Great to use WordPress as a CMS, and create pages with several categories posts. <br/><br/>Inspired by Category Page: http://wordpress.org/extend/plugins/page2cat/<br/>Category Page is a good plugin, but too complicated and big for what I needed. I just needed to list posts from a certain category, and be able to use several category id's to list on one page. 
Version: 0.2
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



function list_category_posts($content){
global $post;
	if ( stristr( $content, '[catlist' )) {
		$search = "@(?:<p>)*\s*\[catlist\s*=\s*(\w+|^\+)\]\s*(?:</p>)*@i";
		if	(preg_match_all($search, $content, $matches)) {
			if (is_array($matches)) {
				$limit = get_option('lcp_limit');		
				foreach ($matches[1] as $key =>$v0) {
					$output = "<ul class='lcp_catlist'>";
					$catposts = get_posts('category='.$v0."&numberposts=".$limit);
					foreach($catposts as $single):
						$output .= "<li><a href='".get_permalink($single->ID)."'>".$single->post_title."</a></li>";
					endforeach;
					$search = $matches[0][$key];
					$output .= "</ul>";
					$replace= $output;
					$content= str_replace ($search, $replace, $content);
				}
			}
		}
	}
return $content;
}

function lcp_add_option_page(){
	add_options_page('List Category Posts', 'List Category Posts', 'manage_options','list-category-posts/list_cat_posts_options.php');
}

//Sidebar widget zone:
function lcp_load_widget() {
	if (function_exists('register_sidebar_widget')) {
		register_sidebar_widget('List category posts', 'lcp_widget');
		register_widget_control('List category posts', 'lcp_widget_options', 300, 200 );
	}
}

function lcp_widget(){//Display
	$result = '<ul class="lcp_catlist">';
	$catposts = get_posts('category='.$ID.'&numberposts='.$NUMBEROFPOSTS);
	foreach($catposts as $single):
		$result.='<li><a href="'.get_permalink($single->ID).'">'.$single->post_title.'</a></li>';
	endforeach;
	$result .= "</ul>";
	echo $result;
}

function lcp_widget_options(){
	include('lcp_widget_form.php');
	}

add_filter('the_content','list_category_posts');
add_action('admin_head', 'lcp_add_option_page');
add_action('plugins_loaded', 'lcp_load_widget');


?>
