<?php
/**
 * List Category Posts sidebar widget.
 * @author fernando@picandocodigo.net
 */
require_once 'CatListDisplayer.php';

class ListCategoryPostsWidget extends WP_Widget{

  function ListCategoryPostsWidget() {
    $opts = array('description' => __('List posts from a specified category','list-category-posts') );
    parent::WP_Widget(false, $name = __('List Category Posts','list-category-posts'), $opts);
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
    $excerptsize = (empty($instance['excerpt_size']) ? 55 : $instance['excerpt_size']);
    $showauthor = ($instance['show_author'] == 'on') ? 'yes' : 'no';
    $showcatlink = ($instance['show_catlink'] == 'on') ? 'yes' : 'no';
    $thumbnail = ($instance['thumbnail'] == 'on') ? 'yes' : 'no';
    $thumbnail_size = ($instance['thumbnail_size']) ? $instance['thumbnail_size'] : 'thumbnail';
    $morelink = empty($instance['morelink']) ? ' ' : $instance['morelink'];




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
      'excerpt_size' => $excerptsize,
      'exclude' => $exclude,
      'excludeposts' => $excludeposts,
      'offset' => $offset,
      'catlink' => $showcatlink,
      'thumbnail' => $thumbnail,
      'thumbnail_size' => $thumbnail_size,
      'morelink' => $morelink
    );

    echo $before_widget;

    if($morelink!='')//check if the user has setup the morelink;
    
    {
        	//if the user has setup the morelink,display it right beside the title;
          $current_category_link=get_category_link($category_id);
		      echo $before_title.$title .'<a href="'.$current_category_link.'" id="more">'.$morelink.'</a>'.$after_title;
    
    }

    else
    
    {
      
         //if the user hasn't set the morelink,just display the title alone.
         echo $before_title.$title.$after_title;
    
    }


    $catlist_displayer = new CatListDisplayer($atts);
    echo  $catlist_displayer->display();
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
    $instance['excerpt_size'] = strip_tags($new_instance['excerpt_size']);
    $instance['show_author'] = strip_tags($new_instance['show_author']);
    $instance['show_catlink'] = strip_tags($new_instance['show_catlink']);
    $instance['show_catlink'] = strip_tags($new_instance['show_catlink']);
    $instance['thumbnail'] = strip_tags($new_instance['thumbnail']);
    $instance['thumbnail_size'] = strip_tags($new_instance['thumbnail_size']);
    $instance['morelink'] = strip_tags($new_instance['morelink']);

    return $instance;
  }

  /** @see WP_Widget::form */
  function form($instance) {
    include('lcp_widget_form.php');
  }
}

add_action('widgets_init', create_function('', 'return register_widget("listCategoryPostsWidget");'));
?>
