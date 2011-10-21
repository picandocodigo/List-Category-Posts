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
require_once 'CatListDisplayer.php';

class ListCategoryPostsWidget extends WP_Widget{

	function ListCategoryPostsWidget() {
                $opts = array('description' => '');
		parent::WP_Widget(false, $name = 'List Category Posts', $opts);
	}

	function widget($args, $instance) {
		extract( $args );
                $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
		$limit = (is_numeric($instance['limit'])) ? $instance['limit'] : 5;
		$orderby = ($instance['orderby']) ? $instance['orderby'] : 'date';
		$order = ($instance['order']) ? $instance['order'] : 'desc';
		$exclude = ($instance['exclude'] != '') ? $instance['exclude'] : 0;
		$excludeposts = ($instance['excludeposts'] != '') ? $instance['excludeposts'] : 0;
		$offset = (is_numeric($instance['offset'])) ? $instance['offset'] : 0;
		$category_id = $instance['categoryid'];
		$dateformat = ($instance['dateformat']) ? $instance['dateformat'] : get_option('date_format');
                $showdate = ($instance['show_date'] == 'on') ? 'yes' : 'no';
                $showexcerpt = ($instance['show_excerpt'] == 'on') ? 'yes' : 'no';
                $showauthor = ($instance['show_author'] == 'on') ? 'yes' : 'no';
                $showcatlink = ($instance['show_catlink'] == 'on') ? 'yes' : 'no';

                echo $before_widget;
                echo $before_title . $title . $after_title;
                $atts = array(
                        'id' => $category_id,
			'orderby' => $orderby,
			'order' => $order,
			'numberposts' => $limit,
			'date' => $showdate,
			'author' => $showauthor,
			'dateformat' => $dateformat,
			'template' => 'default',
			'excerpt' => $showexcerpt,
			'exclude' => $exclude,
			'excludeposts' => $excludeposts,
			'offset' => $offset,
			//'tags' => '',
			//'content' => 'no',
                        'catlink' => $showcatlink
                    );
                $catlist_displayer = new CatListDisplayer($atts);
                echo  $catlist_displayer->display();
                echo $lcp_result;
		echo $after_widget;
	}

	/** @see WP_Widget::update */
	function update($new_instance, $old_instance) {
                $instance = $old_instance;
                $instance['title'] = strip_tags($new_instance['title']);
                $instance['limit'] = strip_tags($new_instance['limit']);
                $instance['orderby'] = strip_tags($new_instance['orderby']);
                $instance['order'] = strip_tags($new_instance['order']);
                $instance['exclude'] = strip_tags($new_instance['exclude']);
                $instance['excludeposts'] = strip_tags($new_instance['excludeposts']);
                $instance['offset'] = strip_tags($new_instance['offset']);
                $instance['categoryid'] = strip_tags($new_instance['categoryid']);
                $instance['dateformat'] = strip_tags($new_instance['dateformat']);
                $instance['show_date'] = strip_tags($new_instance['show_date']);
                $instance['show_excerpt'] = strip_tags($new_instance['show_excerpt']);
                $instance['show_author'] = strip_tags($new_instance['show_author']);
                $instance['show_catlink'] = strip_tags($new_instance['show_catlink']);

                return $instance;
	}

	/** @see WP_Widget::form */
	function form($instance) {
		include('lcp_widget_form.php');
	}
}
add_action('widgets_init', create_function('', 'return register_widget("listCategoryPostsWidget");'));

?>
