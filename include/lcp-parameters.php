<?php
require_once ( LCP_PATH . 'lcp-utils.php' );

class LcpParameters{
  // Singleton implementation
  private static $instance = null;
  private $starting_with = null;
  // $date_query tells us if we need to generate date_query args
  private $date_query = false;
  private $utils;
  private $params;

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
      'orderby' => $params['orderby'],
      'order' => $params['order'],
      'offset' => $params['offset']
    );

    if( get_option('lcp_orderby') && $params['orderby'] === ''){
      $orderby = array('orderby' => get_option('lcp_orderby'));
      $args = array_merge($args, $orderby);
    }

    if( get_option('lcp_order') && $params['order'] === ''){
      $order = array('order' => get_option('lcp_order'));
      $args = array_merge($args, $order);
    }

    $this->utils = new LcpUtils($params);

    // Check posts to exclude
    $args = $this->lcp_check_excludes($args);

    // Check type, status, parent params
    $args = $this->lcp_types_and_statuses($args);

    if($this->utils->lcp_not_empty('year')):
      $args['year'] = $params['year'];
    endif;

    if($this->utils->lcp_not_empty('monthnum')):
      $args['monthnum'] = $params['monthnum'];
    endif;

    if($this->utils->lcp_not_empty('search')):
      $args['s'] = $params['search'];
    endif;

    if($this->utils->lcp_not_empty('author_posts')):
      $args['author_name'] = $params['author_posts'];
    endif;

    // Posts within given date range:
    if ( $this->utils->lcp_not_empty('after') ) {
      $this->after = $params['after'];
      $date_query = true;
    }

    if ( $this->utils->lcp_not_empty('after_year') ) {
      $this->after_year = $params['after_year'];
      $date_query = true;
    }

    if ( $this->utils->lcp_not_empty('after_month') ) {
      if ($params['after_month'] >= 1 && $params['after_month'] <= 12) {
        $this->after_month = $params['after_month'];
        $date_query = true;
      }
    }

    if ( $this->utils->lcp_not_empty('after_day') ) {
      if ($params['after_day'] >= 1 && $params['after_day'] <= 31) {
        $this->after_day = $params['after_day'];
        $date_query = true;
      }
    }

    if ( $this->utils->lcp_not_empty('before') ) {
      $this->after = $params['before'];
      $date_query = true;
    }

    if ( $this->utils->lcp_not_empty('before_year') ) {
      $this->before_year = $params['before_year'];
      $date_query = true;
    }

    if ( $this->utils->lcp_not_empty('before_month') ) {
      if ($params['before_month'] >= 1 && $params['before_month'] <= 12) {
        $this->before_month = $params['before_month'];
        $date_query = true;
      }
    }

    if ( $this->utils->lcp_not_empty('before_day') ) {
      if ($params['before_day'] >= 1 && $params['before_day'] <= 31) {
        $this->before_day = $params['before_day'];
        $date_query = true;
      }
    }

    // Only generate date_query args if a before/after paramater found
    if ($date_query) {
      $args['date_query'] = $this->create_date_query_args();
    }

    /*
     * Custom fields 'customfield_name' & 'customfield_value'
     * should both be defined
     */
    if( $this->utils->lcp_not_empty('customfield_value') ){
      $args['meta_key'] = $params['customfield_name'];
      $args['meta_value'] = $params['customfield_value'];
    }

    //Get private posts
    if( is_user_logged_in() ){
      if ( !empty($args['post_status']) ){
        $args['post_status'] = array_merge($args['post_status'], array('private'));
      } else{
        $args['post_status'] = array('private', 'publish');
      }
    }

    if ( $this->utils->lcp_not_empty('exclude_tags') ){
      $args = $this->lcp_excluded_tags($params);
    }

    // Current tags
    if ( $this->utils->lcp_not_empty('currenttags') && $params['currenttags'] == "yes" ){
      $tags = $this->lcp_get_current_tags();
      if ( !empty($tags) ){
        $args['tag__in'] = $tags;
      }
    }

    // Custom taxonomy support
    // Why didn't I document this?!?
    if ( $this->utils->lcp_not_empty('taxonomy') && $this->utils->lcp_not_empty('terms') ){
      $args['tax_query'] = array(array(
        'taxonomy' => $params['taxonomy'],
        'field' => 'slug',
        'terms' => explode(",",$params['terms'])
      ));
    }

    // Tag support
    if ( $this->utils->lcp_not_empty('tags') ) {
      $args['tag'] = $params['tags'];
    }

    if ( !empty($params['exclude'])){
      $args['category__not_in'] = array($params['exclude']);
    }

    if ( $this->utils->lcp_not_empty('customfield_orderby') ){
      $args['orderby'] = 'meta_value';
      $args['meta_key'] = $params['customfield_orderby'];
    }

    // Posts that start with a given letter:
    if ( $this->utils->lcp_not_empty('starting_with') ){
      $this->starting_with = $params['starting_with'];
      add_filter('posts_where' , array( $this, 'starting_with') );
    }

    return $args;
  }

  // Check posts to exclude
  private function lcp_check_excludes($args){
    if( $this->utils->lcp_not_empty('excludeposts') ){
      $exclude = array(
        'post__not_in' => explode(",", $this->params['excludeposts'])
      );
      if (strpos($this->params['excludeposts'], 'this') > -1){
        $exclude = array_merge(
          $exclude,
          array('post__not_in' => array($this->lcp_get_current_post_id() ) )
        );
      }
      $args = array_merge($args, $exclude);
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

    if($this->utils->lcp_not_empty('post_parent')):
      $args['post_parent'] = $this->params['post_parent'];
    endif;
    return $args;
  }

  private function lcp_excluded_tags($args){
    $excluded_tags = explode(",", $args['exclude_tags']);
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
    $where .= 'AND (wp_posts.post_title ' .
      'COLLATE UTF8_GENERAL_CI LIKE \'' . $letters[0] . "%'";
    for ($i=1; $i <sizeof($letters); $i++) {
      $where .= 'OR wp_posts.post_title ' .
        'COLLATE UTF8_GENERAL_CI LIKE \'' . $letters[$i] . "%'";
    }
    $where.=')';
    return $where;
  }

  private function lcp_get_current_post_id(){
    global $post;
    return $post->ID;
  }

  /*
   * Create date_query args according to https://codex.wordpress.org/Class_Reference/WP_Query#Date_Parameters
   * There's probably a better way to check if values exist.
   * Code should be cleaned up (this is first attempt at a solution).
   */
  private function create_date_query_args() {
    $date_query = array();
    $params_set = array(
      'after' => false,
      'after_year' => false,
      'after_month' => false,
      'after_day' => false,
      'before' => false,
      'before_year' => false,
      'before_month' => false,
      'before_day' => false,
    );
    $after = false;
    $before = false;

    if ( isset($this->after) ) {
      $params_set['after'] = true;
      $after = true;
    }

    if ( isset($this->after_year) ) {
      $params_set['after_year'] = true;
      $after = true;
    }

    if ( isset($this->after_month) ) {
      $params_set['after_month'] = true;
      $after = true;
    }

    if ( isset($this->after_day) ) {
      $params_set['after_day'] = true;
      $after = true;
    }

    if ( isset($this->before) ) {
      $params_set['before'] = true;
      $before = true;
    }

    if ( isset($this->before_year) ) {
      $params_set['before_year'] = true;
      $before = true;
    }

    if ( isset($this->before_month) ) {
      $params_set['before_month'] = true;
      $before = true;
    }

    if ( isset($this->before_day) ) {
      $params_set['before_day'] = true;
      $before = true;
    }

    if ($after) {
      if ($params_set['after']) {
        $date_query['after'] = $this->after;
      } else {
        if ( $params_set['after_year'] ) $date_query['after']['year'] = $this->after_year;
        if ( $params_set['after_month'] ) $date_query['after']['month'] = $this->after_month;
        if ( $params_set['after_day'] ) $date_query['after']['day'] = $this->after_day;
      }
    }

    if ($before) {
      if ($params_set['before']) {
        $date_query['before'] = $this->before;
      } else {
        if ( $params_set['before_year'] ) $date_query['before']['year'] = $this->before_year;
        if ( $params_set['before_month'] ) $date_query['before']['month'] = $this->before_month;
        if ( $params_set['before_day'] ) $date_query['before']['day'] = $this->before_day;
      }
    }

    return $date_query;
  }
}
