<?php
require_once LCP_PATH . 'lcp-utils.php';
require_once LCP_PATH . 'lcp-date-query.php';
require_once LCP_PATH . 'lcp-meta-query.php';

class LcpParameters{
  // Singleton implementation
  private static $instance = null;
  private $starting_with = null;
  private $utils;
  private $params;

  // Use Trait for before/after date queries:
  use LcpDateQuery;
  // Use Trait for meta query
  use LcpMetaQuery;

  public static function get_instance(){
    if( !isset( self::$instance ) ){
      self::$instance = new self;
    }
    return self::$instance;
  }

  public function get_query_params($params){
    $this->params = $params;
    # Essential parameters:
    $args = array(
      'numberposts' => $params['numberposts'],
      'orderby' => $this->lcp_order_by($params['orderby']),
      'order' => $this->lcp_order($params['order']),
      'offset' => $params['offset']
    );

    $this->utils = new LcpUtils($params);

    // Check posts to exclude
    $args = $this->lcp_check_excludes($args);

    // Check posts to include
    if( $this->utils->lcp_not_empty('includeposts') ){
      $args['post__in'] = explode(",", $this->params['includeposts']);
    }

    // Check type, status, parent params
    $args = $this->lcp_types_and_statuses($args);

    if($this->utils->lcp_not_empty('search')){
      $args['s'] = $params['search'];
    }

    if($this->utils->lcp_not_empty('author_posts')):
      $authors = $params['author_posts'];
      if ($authors == 'current_user'){
        $args['author'] =  wp_get_current_user()->ID;
      } else {
        if( false !== strpos($authors,',')){
          $args['author'] = $authors;
        } else {
          $args['author_name'] = $authors;
        }
      }
    endif;

    // Parameters which need to be checked simply, if they exist, add them to
    // final return array ($args)
    $args = $this->lcp_check_basic_params($args);

    // Only generate date_query args if a before/after paramater was found
    $args = $this->create_date_query_args($args, $params);

    $args = $this->create_meta_query_args($args, $params);

    //Get private posts
    if( is_user_logged_in() ){
      if ( !empty($args['post_status']) ){
        $args['post_status'] = array_merge($args['post_status'], array('private'));
      } else{
        $args['post_status'] = array('private', 'publish');
      }
    }

    if ( $this->utils->lcp_not_empty('exclude_tags') ){
      $args = $this->lcp_excluded_tags($args);
    }

    // Current tags
    $currenttags = $params['currenttags'];
    if ( $currenttags === 'yes' || $currenttags === 'all' ) {
      $tags = $this->lcp_get_current_tags();

      if ( !empty($tags) ) {
        // OR relationship
        if ( 'yes' === $currenttags ) {
          $args['tag__in'] = $tags;
        } else {
          // AND relationship
          $args['tag__and'] = $tags;
        }
      } else {
        /*
          Display nothing when a post has no tags.
          Note that this will not prevent sticky posts
          from being shown if they match other query parameters,
          e.g. when no category is specified or a sticky post's
          category matches the one given in `id` or `name`.
          #80
         */
        $args['post__in'] = [0];
      }
    }

    // Custom taxonomy support
    // Why didn't I document this?!?
    if ( $this->utils->lcp_not_empty('taxonomy') && $this->utils->lcp_not_empty('terms') ){
      if ( strpos($params['terms'],'+') !== false ) {
        $terms = explode("+",$params['terms']);
        $operator = 'AND';
      } else {
        $terms = explode(",",$params['terms']);
        $operator = 'IN';
      }

      $args['tax_query'] = array(array(
        'taxonomy' => $params['taxonomy'],
        'field' => 'slug',
        'terms' => $terms,
        'operator' => $operator
      ));
    }

    // Multiple taxonomies support
    $args = $this->lcp_taxonomies($args);

    // Tag support
    if ( $this->utils->lcp_not_empty('tags') ) {
      $args['tag'] = $params['tags'];
    }

    if ( !empty($params['exclude'])){
      $args['category__not_in'] = array($params['exclude']);
    }

    // Posts that start with a given letter:
    if ( $this->utils->lcp_not_empty('starting_with') ){
      $this->starting_with = $params['starting_with'];
      add_filter('posts_where' , array( $this, 'starting_with') );
    }

    return $args;
  }

    private function lcp_check_basic_params($args){
      $simple_args = array('year', 'monthnum', 'after');
      foreach($simple_args as $key){
        if($this->utils->lcp_not_empty($key)){
            $args[$key] = $this->params[$key];
        }
      }
      return $args;
    }

  // Check posts to exclude
  private function lcp_check_excludes($args){
    if( $this->utils->lcp_not_empty('excludeposts') ){
      $excludeposts = explode(',', $this->params['excludeposts']);

      $this_index = array_search("this", $excludeposts);

      if ($this_index > -1){
        unset($excludeposts[$this_index]);
        $excludeposts = array_merge(
          $excludeposts,
          array($this->lcp_get_current_post_id())
        );
      }
      $excludeposts = array(
        'post__not_in' => $excludeposts
      );
      $args = array_merge($args, $excludeposts);
    }
    return $args;
  }

    private function lcp_taxonomies($args){
      // Multiple taxonomies support in the form
      // taxonomies_or="tax1:{term1_1,term1_2};tax2:{term2_1,term2_2,term2_3}"
      // taxonomies_and="tax1:{term1_1,term1_2};tax2:{term2_1,term2_2,term2_3}"
      if ( $this->utils->lcp_not_empty('taxonomies_or') ||
           $this->utils->lcp_not_empty('taxonomies_and') ) {
        if($this->utils->lcp_not_empty('taxonomies_or')) {
          $operator = "OR";
          $taxonomies = $this->params['taxonomies_or'];
        } else {
          $operator = "AND";
          $taxonomies = $this->params['taxonomies_and'];
        }
        $count = preg_match_all('/([^:]+):\{([^:]+)\}(?:;|$)/im', $taxonomies, $matches, PREG_SET_ORDER, 0);
        if($count > 0) {
          $tax_arr = array('relation' => $operator);
          foreach ($matches as $match) {
            $tax_term = array(
              'taxonomy' => $match[1],
              'field' => 'slug',
              'terms' => explode(",",$match[2])
            );
            array_push($tax_arr, $tax_term);
          }
          $args['tax_query'] = $tax_arr;
        }
      }
      return $args;
    }

  private function lcp_types_and_statuses($args){
    // Post type, status, parent params:
    if($this->utils->lcp_not_empty('post_type')):
      $args['post_type'] = explode( ',', $this->params['post_type'] );
    endif;

    if($this->utils->lcp_not_empty('post_status')):
      $args['post_status'] = explode( ',', $this->params['post_status'] );
    endif;

    if( '' !== $this->params[ 'post_parent' ] ):
      $args['post_parent'] = $this->params['post_parent'];
    endif;
    return $args;
  }

  private function lcp_excluded_tags($args){
    $excluded_tags = explode(",", $this->params['exclude_tags']);
    $tag_ids = array();
    foreach ( $excluded_tags as $excluded){
      $tag_ids[] = get_term_by('slug', $excluded, 'post_tag')->term_id;
    }
    $args['tag__not_in'] = $tag_ids;
    return $args;
  }

  private function lcp_get_current_tags(){
    $tags = get_the_tags();
    $tag_ids = array();
    if( !empty($tags) ){
      foreach ($tags as $tag) {
        array_push($tag_ids, $tag->term_id);
      }
    }
    return $tag_ids;
  }

  public function starting_with($where){
    $letters = explode(',', $this->starting_with);

    // Support for both utf8 and utf8mb4
    global $wpdb;
    $wp_posts_prefix = $wpdb->prefix . 'posts';
    $charset = $wpdb->get_col_charset($wp_posts_prefix, 'post_title');

    $where .= 'AND (' . $wp_posts_prefix . '.post_title ' .
      'COLLATE ' . strtoupper($charset) . '_GENERAL_CI LIKE \'' . $letters[0] . "%'";
    for ($i=1; $i <sizeof($letters); $i++) {
      $where .= 'OR ' . $wp_posts_prefix . '.post_title ' .
        'COLLATE ' . strtoupper($charset) . '_GENERAL_CI LIKE \'' . $letters[$i] . "%'";
    }
    $where.=')';
    return $where;
  }

  private function lcp_get_current_post_id(){
    global $post;
    return $post->ID;
  }



  private function lcp_order_by($orderby) {
    if( get_option('lcp_orderby') && $orderby === ''){
      return get_option('lcp_orderby');
    }
    return $orderby;
  }

  private function lcp_order($order) {
    if( get_option('lcp_order') && $order === '') {
      return get_option('lcp_order');
    }
    return $order;
  }
}
