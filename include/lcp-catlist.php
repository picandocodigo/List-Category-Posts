<?php
define( 'LCP_PATH', plugin_dir_path( __FILE__ ) );
require_once ( LCP_PATH . 'lcp-thumbnail.php' );
require_once ( LCP_PATH . 'lcp-parameters.php' );
require_once ( LCP_PATH . 'lcp-utils.php' );
require_once ( LCP_PATH . 'lcp-category.php' );
require_once ( LCP_PATH . 'lcp-paginator.php' );

/**
 * The CatList object gets the info for the CatListDisplayer to show.
 * Each time you use the shortcode, you get an instance of this class.
 * @author fernando@picandocodigo.net
 */
class CatList{
  private $params = array();
  private $lcp_category_id = 0;
  private $page = 1;
  private $posts_count = 0;
  private $instance = 0;
  private $utils;
  private $wrapper;

  /**
   * Constructor gets the shortcode attributes as parameter
   * @param array $atts
   */
  public function __construct($atts) {
    load_plugin_textdomain(
      'list-category-posts',
      false,
      dirname( plugin_basename( __FILE__ ) ) . '/languages/'
    );
    $this->params = $atts;
    $this->utils = new LcpUtils($this->params);
    $this->wrapper = LcpWrapper::get_instance();

    if ( $this->utils->lcp_not_empty('instance') ){
      $this->instance = $atts['instance'];
    }
  }

  /**
   * Determine the categories of posts and execute the WP_query
   */
  public function get_posts() {
    $this->set_lcp_parameters();
  }

  /**
   * Save the existing wp_query
   */
  public function save_wp_query() {
    global $wp_query;
    if ( isset($wp_query) ) {
      $this->saved_wp_query = clone $wp_query;
      global $post;
      $this->saved_post = $post;
    }
  }

  /**
   * Restore the previous wp_query
   */
  public function restore_wp_query() {
    global $wp_query;
    if ( isset($this->saved_wp_query) ) {
      $wp_query = clone $this->saved_wp_query;
      global $post;
      $post = $this->saved_post;
    }
  }

  /**
   * Order the parameters and query the DB for posts
   */
  private function set_lcp_parameters(){
    $args = LcpCategory::get_instance()->get_lcp_category([
      'id'               => $this->params['id'],
      'name'             => $this->params['name'],
      'categorypage'     => $this->params['categorypage'],
      'child_categories' => $this->params['child_categories'],
    ], $this->lcp_category_id);
    $processed_params = LcpParameters::get_instance()->get_query_params($this->params);
    $args = array_merge($args, $processed_params);
    $args = $this->check_pagination($args);

    // for WP_Query compatibility
    // http://core.trac.wordpress.org/browser/tags/3.7.1/src/wp-includes/post.php#L1686
    $args['posts_per_page'] = $args['numberposts'];

    query_posts($args);
    global $wp_query;
    $this->posts_count = $wp_query->found_posts;
    remove_all_filters('posts_orderby');
    remove_filter('posts_where', array(LcpParameters::get_instance(), 'starting_with'));
  }

  /* Should I return posts or show that the tag/category or whatever
     posts combination that I called has no posts? By default I've
     always returned the latest posts because that's what the query
     does when the params are "wrong". But could make for a better user
     experience if I returned an empty list in certain cases.
     private function lcp_should_return_posts() */

  /** HELPER FUNCTIONS **/

  private function check_pagination($args) {
    if (LcpUtils::lcp_show_pagination($this->params['pagination'])) {
      if(array_key_exists('QUERY_STRING', $_SERVER) && null !== $_SERVER['QUERY_STRING'] ) {
        $query = $_SERVER['QUERY_STRING'];
        if ($query !== '' && preg_match('/lcp_page' . preg_quote($this->instance) .
                                        '=([0-9]+)/i', $query, $match)) {
          $this->page = $match[1];
          $offset = ($this->page - 1) * $this->params['numberposts'];
          $args = array_merge($args, array('offset' => $offset));
        }
      }
    }
    return $args;
  }

  public function get_category_id() {
    return $this->lcp_category_id;
  }

  /**
   * This is no longer used by templates. Keeping for
   * backward compatibility with old templates.
   */
  public function get_categories_posts(){
    global $wp_query;
    return $wp_query->get_posts();
  }

  /**
   * Load category name and link to the category:
   */
  public function get_category_link() {
    if(($this->params['catlink'] == 'yes' ||
        $this->params['catname'] == 'yes') &&
       $this->lcp_category_id != 0){
      // Check for one id or several:
      $ids = null;
      if (is_array($this->lcp_category_id)) {
        $ids = $this->lcp_category_id;
      } else {
        $ids = explode(",", $this->lcp_category_id);
      }

      $link = array();
      // Loop on several categories:
      foreach($ids as $lcp_id) {
        $cat_link = get_category_link($lcp_id);
        $cat_title = get_cat_name($lcp_id);

        // Use the category title or 'catlink_string' set by user:
        if ($this->utils->lcp_not_empty('catlink_string')){
          $cat_string = $this->params['catlink_string'];
        } else {
          $cat_string = $cat_title;
        }

        // Do we want the link or just the title?
        if ($this->params['catlink'] == 'yes') {
          $cat_string = $this->wrapper->to_html(
            'a',
            ['href' => $cat_link, 'title' => $cat_title],
            $cat_string . $this->get_category_count()
          );
        }

        array_push($link, $cat_string);
      }
      return implode(", ", $link);
    } else {
      return null;
    }
  }

  /**
   * Load morelink name and link to the category:
   */
  public function get_morelink() {
    if (!empty($this->params['morelink'])) {
      $props = ['href=' => get_category_link($this->lcp_category_id)];
      $readmore = ($this->params['morelink'] !== '' ? $this->params['morelink'] : 'More posts');
      return $this->wrapper->to_html('a', $props, $readmore);
     } else {
      return null;
    }
  }

  public function get_posts_morelink($single, $css_class) {
    if(!empty($this->params['posts_morelink'])) {
      $props = ['href' => get_permalink($single->ID)];
      $class = $css_class ?: "";
      if ($class) {
        $props['class'] = $class;
      }
      $readmore = $this->params['posts_morelink'];
      return $this->wrapper->to_html('a', $props, $readmore);
    }
  }

  public function get_category_count() {
    if($this->utils->lcp_not_empty('category_count') && $this->params['category_count'] == 'yes') {
      return ' ' . get_category($this->lcp_category_id)->category_count;
    }
  }

  public function get_category_description() {
    if ($this->utils->lcp_not_empty('category_description') && $this->params['category_description'] == 'yes') {
      return get_term_field('description', $this->lcp_category_id, '', 'raw');
    }
  }

  public function get_conditional_title() {
    if($this->utils->lcp_not_empty('conditional_title') && $this->get_posts_count() > 0) {
      return trim($this->params['conditional_title']);
    }
  }

  /**
   * Array of custom fields.
   * @see http://codex.wordpress.org/Function_Reference/get_post_custom
   * @param string $custom_key
   * @param int $post_id
   */
  public function get_custom_fields($custom_key, $post_id) {
    if ($this->utils->lcp_not_empty('customfield_display')) {
      $lcp_customs = array();

      //Doesn't work for many custom fields when having spaces:
      $custom_key = trim($custom_key);

      //Create array for many fields:
      $custom_array = explode(',', $custom_key);

      //Get post custom fields:
      $custom_fields = get_post_custom($post_id);

      //Loop on custom fields and if there's a value, add it:
      foreach ($custom_array as $user_customfield) {
        // Check that the custom field is wanted:
        if (isset( $custom_fields[$user_customfield])) {
          //Browse through the custom field values:
          foreach ($custom_fields[$user_customfield] as $key => $value) {
            if ($this->params['customfield_display_name'] != 'no' && $value !== '' ){
              $value = $user_customfield . $this->params['customfield_display_name_glue'] . $value;
            }
            if($value != '') {
              $lcp_customs[] = $value;
            }
          }
        }
      }

      // Return a string instead of array if custom fields
      // are not displayed separately.
      if ($this->params['customfield_display_separately'] === 'no') {
        $lcp_customs = implode($this->params['customfield_display_glue'], $lcp_customs);
      }

      return $lcp_customs;
    } else {
      return null;
    }
  }

  public function get_comments_count($single) {
    if (isset($this->params['comments']) &&
        $this->params['comments'] == 'yes') {
      return ' (' . $single->comment_count . ')';
    } else {
      return null;
    }
  }

  public function get_author_to_show($single) {
    if ($this->params['author'] == 'yes') {
      $lcp_userdata = get_userdata($single->post_author);
      $author_name =  $lcp_userdata->display_name;
      if($this->utils->lcp_not_empty('author_posts_link') &&
         $this->params['author_posts_link'] == 'yes') {
        $link = get_author_posts_url($lcp_userdata->ID);
        return $this->wrapper->to_html(
          'a',
          ['href' => $link, 'title' => $author_name],
          $author_name
        );
      } else {
        return $author_name;
      }
    } else {
      return null;
    }
  }


  /** Pagination **/
  public function get_page() {
    return $this->page;
  }

  // Helper method for tests.
  public function update_page($page) {
    $this->page = $page;
  }

  public function get_posts_count() {
    return $this->posts_count;
  }

  public function get_number_posts() {
    return $this->params['numberposts'];
  }

  public function get_instance() {
    return $this->instance;
  }

  public function get_date_to_show($single) {
    if ($this->params['date'] == 'yes') {
      //by Verex, great idea!
      return ' ' . get_the_time($this->params['dateformat'], $single);
    } else {
      return null;
    }
  }

  public function get_modified_date_to_show($single) {
    if ($this->params['date_modified'] == 'yes') {
      return " " . get_the_modified_time($this->params['dateformat'], $single);
    } else {
      return null;
    }
  }

  public function get_display_id($single) {
    if (!empty($this->params['display_id']) && $this->params['display_id'] == 'yes') {
      return $single->ID;
    }
  }

  public function get_no_posts_text() {
    if (($this->get_posts_count() == 0) &&
        ($this->params["no_posts_text"] != '')) {
      return $this->params["no_posts_text"];
    }
  }

  public function get_content($single) {
    if (isset($this->params['content']) &&
        ($this->params['content'] =='yes' || $this->params['content'] == 'full') &&
        $single->post_content){
      // get_extended - get content split by <!--more-->
      $lcp_extended = get_extended($single->post_content);
      $lcp_content = $lcp_extended['main'];
      $lcp_content = apply_filters('the_content', $lcp_content);
      $lcp_content = str_replace(']]>', ']]&gt', $lcp_content);

      if ($this->params['content'] == 'full') {
        $lcp_extended_content = str_replace(
          ']]>',
          ']]&gt', apply_filters('the_content', $lcp_extended['extended'])
        );
        $lcp_content .= $lcp_extended_content;
      } else {
        if (empty($this->params['posts_morelink'])) {
          $lcp_more = __('Continue reading &rarr;', 'list-category-posts');
          $lcp_content .= $this->wrapper->to_html(
            'a',
            ['href' => get_permalink($single->ID), 'title' => $lcp_more],
            $lcp_more
          );
        }
      }
      return $lcp_content;
    } else {
        return null;
    }
  }

  public function get_excerpt($single) {
    if (!empty( $this->params['excerpt']) &&
         ($this->params['excerpt']=='yes' || $this->params['excerpt']=='full')) {

      if($single->post_excerpt == "" ||
         (!empty($this->params['excerpt_overwrite']) && $this->params['excerpt_overwrite'] == 'yes')) {
        // No explicit excerpt or excerpt_overwrite=yes, so generate from content:
        $lcp_content = $single->post_content;
        // <!--more--> tag?
        if($this->params['excerpt']=='full' &&
          preg_match('/[\S\s]+(<!--more(.*?)?-->)[\S\s]+/', $lcp_content, $matches)) {
          $lcp_excerpt = explode($matches[1], $lcp_content);
          $lcp_excerpt = $lcp_excerpt[0];
          $lcp_excerpt = apply_filters('the_excerpt', $lcp_excerpt);
        } else {
          $lcp_excerpt = $this->lcp_trim_excerpt($lcp_content);
        }
      } else {
        // Explicit excerpt and excerpt_overwrite=no:
        if($this->params['excerpt']=='full') {
          $lcp_excerpt = $single->post_excerpt;
        } else {
          $lcp_excerpt = $this->lcp_trim_excerpt($single->post_excerpt);
        }
      }
      if(strlen($lcp_excerpt) < 1 ) {
        $lcp_excerpt = $single->post_title;
      }
      return $lcp_excerpt;
    }
  }

  private function lcp_trim_excerpt($text = '') {
    $excerpt_length = intval($this->params['excerpt_size']);

    $text = strip_shortcodes($text);
    $text = apply_filters('the_excerpt', $text);
    $text = str_replace(']]>',']]&gt;', $text);

    if($this->utils->lcp_not_empty('excerpt_strip') &&
       $this->params['excerpt_strip'] == 'yes') {
      $text = strip_tags($text);
    }

    $words = explode(' ', $text, $excerpt_length + 1);
    if(count($words) > $excerpt_length) {
      array_pop($words);
      array_push($words, '...');
      $text = implode(' ', $words);
    }
    return $text;
  }

  public function get_thumbnail($single, $lcp_thumb_class = null) {
    if ($this->utils->lcp_not_empty('force_thumbnail')) {
      $force_thumbnail = $this->params['force_thumbnail'];
    } else {
      $force_thumbnail = 'no';
    }
    return LcpThumbnail::get_instance()->get_thumbnail(
      $single,
      $this->params['thumbnail'],
      $this->params['thumbnail_size'],
      $force_thumbnail,
      $lcp_thumb_class
    );
  }

  public function get_outer_tag($tag, $css_class) {
    $css_class = $this->params['class'] ?: $css_class;

    $props = [];
    if ($tag == 'ol' && !empty($this->params['ol_offset'])) {
      $props['start'] = $this->params['ol_offset'];
    }

    // Follow the number of posts in an ordered list with pagination.
    if('ol' === $tag && $this->page > 1) {
      $start = $this->get_number_posts() * ($this->page - 1) + 1;
      $props['start'] = $start;
    }
    //Give a class to wrapper tag
    $props['class'] = $css_class;

    //Give id to wrapper tag
    $props['id'] = 'lcp_instance_' . $this->instance;

    return $this->wrapper->to_html($tag, $props, null, false);
  }

  public function get_inner_tag($single, $parent, $tag, $css_class='') {
    $class = $css_class;
    $props = [];
    if (is_object( $parent) && is_object($single) &&
        $parent->ID === $single->ID) {
      $class .= 'current';
    }

    if ($this->params['tags_as_class'] === 'yes') {
      $post_tags = wp_get_post_Tags($single->ID);
      if (!empty($post_tags)) {
        foreach ($post_tags as $post_tag) {
          $class .= " $post_tag->slug ";
        }
      }
    }
    if (!empty($class)) {
      $props['class'] = $class;
    }
    return $this->wrapper->to_html($tag, $props, null, false);
  }

  public function get_pagination() {
    $paginator_params = array(
          'bookmarks'   => $this->params['pagination_bookmarks'],
          'instance'    => $this->get_instance(),
          'next'        => $this->params['pagination_next'],
          'numberposts' => $this->get_number_posts(),
          'padding'     => $this->params['pagination_padding'],
          'page'        => $this->get_page(),
          'pagination'  => $this->params['pagination'],
          'posts_count' => $this->get_posts_count(),
          'previous'    => $this->params['pagination_prev'],
    );
    return LcpPaginator::get_instance()->get_pagination($paginator_params);
  }
}
