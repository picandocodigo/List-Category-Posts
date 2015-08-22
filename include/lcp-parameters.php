<?php
require_once ( LCP_PATH . 'lcp-utils.php' );

class LcpParameters{
  // Singleton implementation
  private static $instance = null;
  private $starting_with = null;
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
    if ( $this->utils->lcp_not_empty('taxonomy') && $this->utils->lcp_not_empty('tags') ){
      $args['tax_query'] = array(array(
        'taxonomy' => $params['taxonomy'],
        'field' => 'slug',
        'terms' => explode(",",$params['tags'])
      ));
    } elseif ( !empty($params['tags']) ) {
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
}