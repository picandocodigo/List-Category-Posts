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

  /*
   * When the category is set using the `name` parameter.
   */
  public function withName($name){
    if ( preg_match('/\+/', $name) ){ // AND relationship
      return $this->andRelationship($name);
    } elseif (preg_match('/,/', $name )){ // OR relationship
      return $this->orRelationship($name);
    }
    return $this->getCategoryIdByName($name);
  }

  public function withId($catId){
    if (preg_match('/\+/', $catId)){
      if ( preg_match('/(-[0-9]+)+/', $catId, $matches) ){
        $this->exclude = implode(',', explode("-", ltrim($matches[0], '-') ));
      }
      return array_map( 'intval', explode( "+", $catId ) );
    }
    return $catId;
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
        return [0]; // workaround to display no posts
      }
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
    }
    return $category->cat_ID;
  }


  /**
   * Get the category id from its name
   * by Eric Celeste / http://eric.clst.org
   */
  private function getCategoryIdByName($categoryName){
    //TODO: Support multiple names (this used to work, but not anymore)
    //We check if the name gets the category id, if not, we check the slug.
    $term = get_term_by('slug', $categoryName, 'category');
    if (!$term){
      $term = get_term_by('name', $categoryName, 'category');
    }
    return ($term) ? $term->term_id : 0;
  }

  private function andRelationship($name){
    $categories = array();
    $catArray = explode("+", $name);

    foreach ($catArray as $category){
      $categories[] = $this->getCategoryIdByName($category);
    }
    return $categories;
  }

  private function orRelationship($name) {
    $categories = '';
    $catArray = explode(",", $name);

    foreach ($catArray as $category){
      $id = $this->getCategoryIdByName($category);
      $categories .= $id . ",";
    }

    return $categories;
  }
}
