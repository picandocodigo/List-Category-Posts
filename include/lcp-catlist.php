<?php
define( 'LCP_PATH', plugin_dir_path( __FILE__ ) );
require_once ( LCP_PATH . 'lcp-thumbnail.php' );
require_once ( LCP_PATH . 'lcp-parameters.php' );
require_once ( LCP_PATH . 'lcp-utils.php' );
require_once ( LCP_PATH . 'lcp-category.php' );

/**
 * The CatList object gets the info for the CatListDisplayer to show.
 * Each time you use the shortcode, you get an instance of this class.
 * @author fernando@picandocodigo.net
 */
class CatList{
  private $params = array();
  private $lcp_category_id = 0;
  private $exclude;
  private $page = 1;
  private $posts_count = 0;
  private $instance = 0;
  private $utils;

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

    if ( $this->utils->lcp_not_empty('instance') ){
      $this->instance = $atts['instance'];
    }
    //Get the category posts:
    $this->get_lcp_category();
    $this->set_lcp_parameters();
  }

  /**
   * Order the parameters and query the DB for posts
   */
  private function set_lcp_parameters(){
    $args = $this->lcp_categories();
    $processed_params = LcpParameters::get_instance()->get_query_params($this->params);
    $args = array_merge($args, $processed_params);
    $args = $this->check_pagination($args);

    // for WP_Query compatibility
    // http://core.trac.wordpress.org/browser/tags/3.7.1/src/wp-includes/post.php#L1686
    $args['posts_per_page'] = $args['numberposts'];

    $query = new WP_Query;
    $this->lcp_categories_posts = $query->query($args);
    $this->posts_count = $query->found_posts;
    remove_all_filters('posts_orderby');
    remove_all_filters('posts_where');
  }

  /* Should I return posts or show that the tag/category or whatever
     posts combination that I called has no posts? By default I've
     always returned the latest posts because that's what the query
     does when the params are "wrong". But could make for a better user
     experience if I returned an empty list in certain cases.
     private function lcp_should_return_posts() */

  /** HELPER FUNCTIONS **/

  private function check_pagination($args){
    if ( $this->utils->lcp_not_empty('pagination') ){
      if(isset($_SERVER['QUERY_STRING']) &&
      !empty($_SERVER['QUERY_STRING']) &&
      ( preg_match('/lcp_page' . preg_quote($this->instance) .
      '=([0-9]+)/i', $_SERVER['QUERY_STRING'], $match) ) ){
        $this->page = $match[1];
        $offset = ($this->page - 1) * $this->params['numberposts'];
        $args = array_merge($args, array('offset' => $offset));
      }
    }
    return $args;
  }

  /**
   * Check if there's one or more categories.
   * Used in the beginning when setting up the parameters.
   */
  private function lcp_categories(){
    if ( is_array($this->lcp_category_id) ){
      return array('category__and' => $this->lcp_category_id);
    } else {
      return array('cat'=> $this->lcp_category_id);
    }
  }

  private function get_lcp_category(){
    // In a category page:
    if ( $this->utils->lcp_not_empty('categorypage') &&
    $this->params['categorypage'] == 'yes' ||
    $this->params['id'] == -1){
      // Use current category
      $this->lcp_category_id = LcpCategory::get_instance()->current_category();
    } elseif ( $this->utils->lcp_not_empty('name') ){
      // Using the category name:
      $this->lcp_category_id = LcpCategory::get_instance()->with_name( $this->params['name'] );
    } elseif ( isset($this->params['id']) && $this->params['id'] != '0' ){
      // Using the id:
      $this->lcp_category_id = LcpCategory::get_instance()->with_id( $this->params['id'] );
    }

  }

  public function get_category_id(){
    return $this->lcp_category_id;
  }

  public function get_categories_posts(){
    return $this->lcp_categories_posts;
  }

  /**
   * Load category name and link to the category:
   */
  public function get_category_link(){
    if(($this->utils->lcp_not_empty('catlink') &&
    $this->params['catlink'] == 'yes' ||
    $this->utils->lcp_not_empty('catname') &&
    $this->params['catname'] == 'yes') &&
    $this->lcp_category_id != 0):
      // Check for one id or several:
      $ids = null;
    if (is_array($this->lcp_category_id)){
      $ids = $this->lcp_category_id;
    } else{
      $ids = explode(",", $this->lcp_category_id);
    }

    $link = array();
    // Loop on several categories:
    foreach($ids as $lcp_id){
      $cat_link = get_category_link($lcp_id);
      $cat_title = get_cat_name($lcp_id);

      // Use the category title or 'catlink_string' set by user:
      if ($this->utils->lcp_not_empty('catlink_string')){
        $cat_string = $this->params['catlink_string'];
      } else {
        $cat_string = $cat_title;
      }

      // Do we want the link or just the title?
      if ($this->params['catlink'] == 'yes'){
        $cat_string = '<a href="' . $cat_link . '" title="' . $cat_title . '">' .
          $cat_string .
          $this->get_category_count($lcp_id) .  '</a>';
      }
      array_push($link, $cat_string);
    }
    return implode(", ", $link);
    else:
      return null;
    endif;
  }

  /**
   * Load morelink name and link to the category:
   */
  public function get_morelink(){
    if (!empty($this->params['morelink'])) :
      $href = 'href="' . get_category_link($this->lcp_category_id) . '"';
    $readmore = ($this->params['morelink'] !== '' ? $this->params['morelink'] : 'More posts');
    return '<a ' . $href . ' >' . $readmore . '</a>';
    else:
      return null;
    endif;
  }



  public function get_category_count($id){
    if($this->utils->lcp_not_empty('category_count') && $this->params['category_count'] == 'yes'):
      return ' (' . get_category($id)->category_count . ')';
    endif;
  }

  /**
   * Display custom fields.
   * @see http://codex.wordpress.org/Function_Reference/get_post_custom
   * @param string $custom_key
   * @param int $post_id
   */
  public function get_custom_fields($custom_key, $post_id){
    if( $this->utils->lcp_not_empty('customfield_display') &&
    ( $this->params['customfield_display'] != '') ):
      $lcp_customs = '';

    //Doesn't work for many custom fields when having spaces:
    $custom_key = trim($custom_key);

    //Create array for many fields:
    $custom_array = explode(",", $custom_key);

    //Get post custom fields:
    $custom_fields = get_post_custom($post_id);

    //Loop on custom fields and if there's a value, add it:
    foreach ($custom_array as $user_customfield) :
    if(isset($custom_fields[$user_customfield])):
      $my_custom_field = $custom_fields[$user_customfield];

    if (sizeof($my_custom_field) > 0 ):
      foreach ( $my_custom_field as $key => $value ) :
      if ($this->params['customfield_display_name'] != "no")
        $lcp_customs .= $user_customfield . " : ";
    $lcp_customs .= $value;
    endforeach;
    endif;
    endif;
    endforeach;

    return $lcp_customs;
    else:
      return null;
    endif;
  }

  public function get_comments_count($single){
    if (isset($this->params['comments']) &&
    $this->params['comments'] == 'yes'):
      return ' (' . $single->comment_count . ')';
    else:
      return null;
    endif;
  }

  public function get_author_to_show($single){
    if ($this->params['author'] == 'yes'):
      $lcp_userdata = get_userdata($single->post_author);
    $author_name =  $lcp_userdata->display_name;
    if($this->utils->lcp_not_empty('author_posts_link') &&
    $this->params['author_posts_link'] == 'yes'){
      $link = get_author_posts_url($lcp_userdata->ID);
      return "<a href=" . $link . " title='" . $author_name .
        "'>" . $author_name . "</a>";
    } else {
      return $author_name;
    }
    else:
      return null;
    endif;
  }


  /** Pagination **/
  public function get_page(){
    return $this->page;
  }

  public function get_posts_count(){
    return $this->posts_count;
  }

  public function get_number_posts(){
    return $this->params['numberposts'];
  }

  public function get_instance(){
    return $this->instance;
  }

  public function get_date_to_show($single){
    if ($this->params['date'] == 'yes'):
      //by Verex, great idea!
      return get_the_time($this->params['dateformat'], $single);
    else:
      return null;
    endif;
  }

  public function get_modified_date_to_show($single){
    if ($this->params['date_modified'] == 'yes'):
      return get_the_modified_time($this->params['dateformat'], $single);
    else:
      return null;
    endif;
  }

  public function get_content($single){
    if (isset($this->params['content']) &&
    ($this->params['content'] =='yes' || $this->params['content'] =='full') &&
    $single->post_content):
      $lcp_content = $single->post_content;
      $lcp_content = apply_filters('the_content', $lcp_content);
      $lcp_content = str_replace(']]>', ']]&gt', $lcp_content);

      if ($this->params['content'] =='yes' &&
        preg_match('/[\S\s]+(<!--more(.*?)?-->)[\S\s]+/', $lcp_content, $matches) ):
        if( empty($this->params['posts_morelink']) ):
          $lcp_more = __('Continue reading &rarr;', 'list-category-posts');
        else:
          $lcp_more = '';
        endif;
        $lcp_post_content = explode($matches[1], $lcp_content);
        $lcp_content = $lcp_post_content[0] . ($lcp_more ?
          ' <a href="' . get_permalink($single->ID) . '" title="' . "$lcp_more" . '">' .
          $lcp_more . '</a>' : '');
      endif;

      return $lcp_content;
    else:
      return null;
    endif;
  }

  public function get_excerpt($single){
    if ( !empty($this->params['excerpt']) && $this->params['excerpt']=='yes'){

      if($single->post_excerpt == ("")){
        //No excerpt, generate one:
        $lcp_excerpt = $this->lcp_trim_excerpt($single->post_content);
      }else{
        if(!empty($this->params['excerpt_overwrite']) &&
        $this->params['excerpt_overwrite'] == 'yes'){
          // Excerpt but we want to overwrite it:";
          $lcp_excerpt = $this->lcp_trim_excerpt($single->post_content);
        } else {
          // Bring post excerpt;
          $lcp_excerpt = $this->lcp_trim_excerpt($single->post_excerpt);
        }
      }

      if( strlen($lcp_excerpt) < 1 ){
        $lcp_excerpt = $single->post_title;
      }
      return $lcp_excerpt;
    }
  }

  private function lcp_trim_excerpt($text = ''){
    $excerpt_length = intval($this->params['excerpt_size']);

    $text = strip_shortcodes($text);
    $text = apply_filters('the_excerpt', $text);
    $text = str_replace(']]>',']]&gt;', $text);

    if( $this->utils->lcp_not_empty('excerpt_strip') &&
    $this->params['excerpt_strip'] == 'yes'):
      $text = strip_tags($text);
    endif;

    $words = explode(' ', $text, $excerpt_length + 1);
    if(count($words) > $excerpt_length) :
      array_pop($words);
    array_push($words, '...');
    $text = implode(' ', $words);
    endif;
    return $text;
  }

  public function get_thumbnail($single, $lcp_thumb_class = null){
    return LcpThumbnail::get_instance()->get_thumbnail(
      $single,
      $this->params['thumbnail'],
      $this->params['thumbnail_size'],
      $lcp_thumb_class);
  }

}
