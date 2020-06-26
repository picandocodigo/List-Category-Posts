<?php
/**
 * This Singleton class has the code for defining which category to
 * use according to the shortcode.
 * @author fernando@picandocodigo.net
 */
class LcpCategory{
  // Singleton implementation
  private static $instance = null;

  public static function get_instance(){
    if( !isset( self::$instance ) ){
      self::$instance = new self;
    }
    return self::$instance;
  }

  public function get_lcp_category($params, &$lcp_category_id) {
    // Only used when excluded categories are combined with 'and' relationship.
    $exclude = [];
    // This will be the value of lcp_category_id which is passed by reference.
    $categories = 0;

    // In a category page:
    if ($params['categorypage'] &&
         in_array($params['categorypage'], ['yes', 'all', 'other']) ||
         $params['id'] == -1) {
      // Use current category
      $categories = $this->current_category($params['categorypage']);
    } elseif ($params['name']) {
      // Using the category name:
      $categories = $this->with_name($params['name']);
    } elseif ($params['id']) {
      // Using the id:
      $categories = $this->with_id($params['id']);
      // If the 'exclude' array was added, excract it.
      if (is_array($categories) && array_key_exists('exclude', $categories)) {
        $exclude = $categories['exclude'];
        unset($categories['exclude']);
      }
    }

    // This is where the lcp_category_id property of CatList is changed.
    $lcp_category_id = $categories;

    return $this->lcp_categories(
      $categories, $params['child_categories'], $exclude);
  }

  /**
   * Check if there's one or more categories.
   * Used in the beginning when setting up the parameters.
   */
  private function lcp_categories($categories, $child_categories, $exclude) {
    $args = array();

    if (is_array($categories)) {
      // Handle excluded categories for the 'and' relationship.
      if ($exclude) {
        $args['category__not_in'] = $exclude;
      }
      $args['category__and'] = $categories;
    } else if (in_array($child_categories, ['no', 'false'])) {
      $args['category__in']= $categories;
    } else {
      $args['cat'] = $categories;
    }
    return $args;
  }

  /*
   * When the category is set using the `name` parameter.
   */
  public function with_name($name){
    if ( preg_match('/\+/', $name) ){ // AND relationship
      return $this->and_relationship($name);
    } elseif (preg_match('/,/', $name )){ // OR relationship
      return $this->or_relationship($name);
    }
    return $this->get_category_id_by_name($name);
  }

  public function with_id($cat_id) {
    if (false !== strpos($cat_id, '+')) {
      if (false !== strpos($cat_id, '-')) {
        /*
         * If the 'and' relationship is used together with excluded
         * categories (eg. 1+2+3-4-5) we parse it with regex and append an
         * array of excluded IDs to the returned array.
         */
        preg_match('/(?P<in>(\+?[0-9]+)+)(?P<ex>(-[0-9]+)+)/', $cat_id, $matches);

        $cat_id = array_map('intval', explode("+", $matches['in']));
        $cat_id['exclude'] = implode(',', explode('-', ltrim($matches['ex'], '-')));
      } else {
        // Simple 'and' relationship, just convert input into an array.
        $cat_id = array_map('intval', explode("+", $cat_id));
      }
    }
    // In all other cases leave user input as is.
    return $cat_id;
  }

  public function current_category($mode){
    // Only single post pages with assigned category and
    // category archives have a 'current category',
    // in all other cases no posts should be returned. (#69)
    $category = get_category( get_query_var( 'cat' ) );
    if( isset( $category->errors ) && $category->errors["invalid_term"][0] == __("Empty Term.") ){
      global $post;
      /* Since WP 4.9 global $post is nullified in text widgets
       * when is_singular() is false.
       *
       * Added in_the_loop check to make the shortcode work
       * in posts listed in archives and home page (#358).
       */
      if ( is_singular() || in_the_loop() ) {
        $categories = get_the_category($post->ID);
      }
      if ( !empty($categories) ){
        $cats = array_map(function($cat) {
          return $cat->cat_ID;
        }, $categories);
        // AND relationship
        if ('all' === $mode) return $cats;
        // OR relationship, default
        if ('yes' === $mode || '' === $mode) return implode(',', $cats);
        // Exclude current categories
        if ('other' === $mode) return implode(',', array_map(function($cat) {
          return "-$cat";
        }, $cats));
      } else {
        return [0]; // workaround to display no posts
      }
    }
    return $category->cat_ID;
  }


  /**
   * Get the category id from its name
   * by Eric Celeste / http://eric.clst.org
   */
  private function get_category_id_by_name($category_name){
    //TODO: Support multiple names (this used to work, but not anymore)
    //We check if the name gets the category id, if not, we check the slug.
    $term = get_term_by('slug', $category_name, 'category');
    if (!$term){
      $term = get_term_by('name', $category_name, 'category');
    }
    return ($term) ? $term->term_id : 0;
  }

  private function and_relationship($name){
    $categories = array();
    $cat_array = explode("+", $name);

    foreach ($cat_array as $category){
      $categories[] = $this->get_category_id_by_name($category);
    }
    return $categories;
  }

  private function or_relationship($name) {
    $categories = array();
    $catArray = explode(",", $name);

    foreach ($catArray as $category){
      $categories[] = $this->get_category_id_by_name($category);
    }

    return implode(',',$categories);
  }
}
