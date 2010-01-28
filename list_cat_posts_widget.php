<?php
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
//Sidebar Widget file

class ListCategoryPostsWidget extends WP_Widget{

	function ListCategoryPostsWidget() {
		parent::WP_Widget(false, $name = 'ListCategoryPostsWidget');
	}

	function widget($args, $instance) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title']);
		$limit = (is_numeric($instance['limit'])) ? $instance['limit'] : 5;
		$orderby = ($instance['orderby']) ? $instance['orderby'] : 'date';
		$order = ($instance['order']) ? $instance['order'] : 'desc';
		$exclude = ($instance['exclude'] != '') ? $instance['exclude'] : 0;
		$excludeposts = ($instance['excludeposts'] != '') ? $instance['excludeposts'] : 0;
		$offset = (is_numeric($instance['offset'])) ? $instance['offset'] : 0;
		$category_id = $instance['categoryid'];
		$dateformat = ($instance['dateformat']) ? $instance['dateformat'] : get_option('date_format');
		
		echo $before_widget;
		
		//Link to the category:
		$cat_link_string = '';
		if ($instance['catlink'] == 'on'){
			$cat_link = get_category_link($category_id);
			$cat_data = get_category($category_id);
			$cat_title = $cat_data->name;
			$cat_link_string = '<a href=' . $cat_link . ' title="' . $cat_title . '">' . $cat_title . '</a>';
		}
		$lcp_result = $cat_link_string;
		
		//Build the query for get_posts()
		$lcp_catposts = get_posts('category=' . $category_id . 
							'&numberposts=' . $limit .
							'&orderby=' . $atts['orderby'] .
							'&order=' . $atts['order'] .
							'&exclude=' . $atts['excludeposts'] .
							'&offset=' . $atts['offset'] );
		
		$lcp_result .= '<ul class="lcp_catlist">';
		
		foreach($lcp_catposts as $lcp_single):
			$lcp_result .= '<li><a href="' . get_permalink($lcp_single->ID).'">' . $lcp_single->post_title . '</a>';
			if($instance['date'] == 'on') :
				$lcp_result .=  ' - ' . get_the_time($atts['dateformat'], $lcp_single);//by Verex, great idea!
			endif;
			if($instance['author'] =='on') :
				$lcp_userdata = get_userdata($lcp_single->post_author);
				$lcp_result .= " - ".$lcp_userdata->user_nicename . '<br/>';
			endif;
			if($instance['excerpt'] == 'on' && $lcp_single->post_excerpt && !($instance['content'] == 'no' && $lcp_single->post_content) ) :
				$lcp_result .= "<p>$lcp_single->post_excerpt</p>";
			endif;
			$lcp_result .= "</li>";
		endforeach;
		
		$lcp_result .= "</ul>";
		echo '<h2>' . $title . '</h2>';
		echo $lcp_result;
		echo $after_widget;
	}

	/** @see WP_Widget::update */
	function update($new_instance, $old_instance) {
		return $new_instance;
	}

	/** @see WP_Widget::form */
	function form($instance) {
		include('lcp_widget_form.php');
	}
}
add_action('widgets_init', create_function('', 'return register_widget("listCategoryPostsWidget");'));

?>
