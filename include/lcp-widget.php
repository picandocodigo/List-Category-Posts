<?php
/**
 * List Category Posts sidebar widget.
 * @author fernando@picandocodigo.net
 */
require_once 'lcp-catlistdisplayer.php';

class ListCategoryPostsWidget extends WP_Widget{

  function __construct() {
    $opts = array('description' => __('List posts from a specified category','list-category-posts') );
    parent::__construct(false, $name = __('List Category Posts','list-category-posts'), $opts);
  }

  function widget($args, $instance) {
    global $post;
    extract( $args );

    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
    $limit = (is_numeric($instance['limit'])) ? $instance['limit'] : 5;
    $orderby = ($instance['orderby']) ? $instance['orderby'] : 'date';
    $order = ($instance['order']) ? $instance['order'] : 'desc';
    $exclude = ($instance['exclude'] != '') ? $instance['exclude'] : 0;
    if($instance['excludeposts'] == 'current')
      $excludeposts = $post->ID;
    if(!isset($excludeposts))
      $excludeposts = ($instance['excludeposts'] != '') ? $instance['excludeposts'] : 0;
    if(!isset($includeposts))
      $includeposts = ($instance['includeposts'] != '') ? $instance['includeposts'] : 0;
    $offset = (is_numeric($instance['offset'])) ? $instance['offset'] : 0;
    $category_id = $instance['categoryid'];
    $dateformat = ($instance['dateformat']) ? $instance['dateformat'] : get_option('date_format');
    $showdate = ($instance['show_date'] == 'on') ? 'yes' : 'no';
    $pagination = ($instance['pagination'] == 'on') ? 'yes' : 'no';
    $showmodifieddate = ($instance['show_modified_date'] == 'on') ? 'yes' : 'no';
    $showexcerpt = ($instance['show_excerpt'] == 'on') ? 'yes' : 'no';
    $excerptsize = (empty($instance['excerpt_size']) ? 55 : $instance['excerpt_size']);
    $showauthor = ($instance['show_author'] == 'on') ? 'yes' : 'no';
    $showcatlink = ($instance['show_catlink'] == 'on') ? 'yes' : 'no';
    $thumbnail = ($instance['thumbnail'] == 'on') ? 'yes' : 'no';
    $thumbnail_size = ($instance['thumbnail_size']) ? $instance['thumbnail_size'] : 'thumbnail';
    $morelink = empty($instance['morelink']) ? ' ' : $instance['morelink'];
    if ( empty( $instance['tags_as_class'] ) ) {
      $instance['tags_as_class'] = 'no';
    }
    $tags_as_class = ($instance['tags_as_class'] == 'yes') ? 'yes' : 'no';
    $template = empty($instance['template']) ? 'default' : $instance['template'];

    $atts = array(
      'id' => $category_id,
      'orderby' => $orderby,
      'order' => $order,
      'numberposts' => $limit,
      'date' => $showdate,
      'date_modified' => $showmodifieddate,
      'author' => $showauthor,
      'dateformat' => $dateformat,
      'template' => 'default',
      'excerpt' => $showexcerpt,
      'excerpt_size' => $excerptsize,
      'exclude' => $exclude,
      'excludeposts' => $excludeposts,
      'includeposts' => $includeposts,
      'offset' => $offset,
      'catlink' => $showcatlink,
      'thumbnail' => $thumbnail,
      'thumbnail_size' => $thumbnail_size,
      'morelink' => $morelink,
      'tags_as_class' => $tags_as_class,
      'template' => $template,
      'pagination_next' => '>>',
      'pagination_prev' => '<<',
      'pagination' => $pagination,
      'instance' => $this->id
    );
    $atts = array_merge(ListCategoryPosts::default_params(), $atts);

    echo $before_widget;

    if ($pagination === 'yes') lcp_pagination_css();

    // To make the widget title replacement work with "Current category" we need to
    // run the displayer here to determine the current cat id.
    // Otherwise the id remains set to "-1".
    $catlist_displayer = new CatListDisplayer($atts);
    $lcp_display = $catlist_displayer->display();

    // Fetch the category id from the Catlist instance.
    $category_id = $catlist_displayer->catlist->get_category_id();
    if ((is_null($category_id) || [0] === $category_id ) &&
        ($title == 'catlink' || $title == 'catname')) {
      $title = '';
    } elseif ($title == 'catlink') {
      // If the user has setup 'catlink' as the title, replace it with
      // the category link:
      $lcp_category = get_category($category_id);
      $title = '<a href="' . get_category_link($lcp_category->cat_ID) . '">' .
        $lcp_category->name . '</a>';
    } elseif ($title == 'catname') {
      // If the user has setup 'catname' as the title, replace it with
      // the category link:
      $lcp_category = get_category($category_id);
      $title = $lcp_category->name;
    }
    echo $before_title . $title . $after_title;

    echo $lcp_display;
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
    $instance['includeposts'] = strip_tags($new_instance['includeposts']);
    $instance['offset'] = strip_tags($new_instance['offset']);
    $instance['categoryid'] = strip_tags($new_instance['categoryid']);
    $instance['dateformat'] = strip_tags($new_instance['dateformat']);
    $instance['show_date'] = strip_tags($new_instance['show_date']);
    $instance['show_modified_date'] = strip_tags($new_instance['show_modified_date']);
    $instance['show_excerpt'] = strip_tags($new_instance['show_excerpt']);
    $instance['excerpt_size'] = strip_tags($new_instance['excerpt_size']);
    $instance['show_author'] = strip_tags($new_instance['show_author']);
    $instance['show_catlink'] = strip_tags($new_instance['show_catlink']);
    $instance['show_catlink'] = strip_tags($new_instance['show_catlink']);
    $instance['thumbnail'] = strip_tags($new_instance['thumbnail']);
    $instance['thumbnail_size'] = strip_tags($new_instance['thumbnail_size']);
    $instance['morelink'] = strip_tags($new_instance['morelink']);
    $instance['tags_as_class'] = strip_tags($new_instance['tags_as_class']);
    $instance['template'] = strip_tags($new_instance['template']);
    $instance['pagination'] = strip_tags($new_instance['pagination']);

    return $instance;
  }

  /** @see WP_Widget::form */
  function form($instance) {
    include('lcp-widget-form.php');
  }
}

function lcp_register_widget() {
  return register_widget("listCategoryPostsWidget");
}
add_action('widgets_init', 'lcp_register_widget');