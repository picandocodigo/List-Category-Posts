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

  /**
   * Used to store the single main category to filter by.
   *
   * @var int
   */
  private $main_cat;

  /**
   * Parses category related shortcode parameters and returns
   * WP_Query compatible $args array. Also sets $lcp_category_id.
   *
   * This method is the main interface of the LcpCategory class. It
   * is currently only used by the CatList class and servers as its helper.
   * $params expects all category related shortcode parameters.
   * $lcp_category_id is **passed by reference** so that it can be
   * changed here. CatList::$lcp_category_id relies on this value heavily.
   *
   * @param  array  $params {
   *   Category related shortcode parameter values.
   *
   *   @type string $id
   *   @type string $name
   *   @type string $categorypage
   *   @type string $child_categories
   *   @type string $main_cat_only
   * }
   * @param  mixed  &$lcp_category_id Optional. Updated by this method if necessary.
   * @return array                    WP_Query $args array, @see lcp_categories.
   */
  public function get_lcp_category($params, &$lcp_category_id=0) {
    // Only used when excluded categories are combined with 'and' relationship.
    $exclude = [];
    // This will be the value of lcp_category_id which is passed by reference.
    $categories = $lcp_category_id;

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

    // Check if only the main category should be used.
    $this->check_main_cat_only( $params[ 'main_cat_only' ], $categories );

    return $this->lcp_categories(
      $categories, $params['child_categories'], $exclude);
  }

  /**
   * Formats the $args array in compliance with WP_Query.
   *
   * This method assigns input category IDs to proper WP_Query $args array.
   * $categories expects an int, string or an array following the logic:
   * - int    -> single category
   * - array  -> 'and' relationship
   * - string -> 'or' relationship (or single cateogry as a string)
   *
   * $child_categories is the value of `child_categories` shortcode param.
   * $exclude is only used when combining 'and' relationship with excluded IDs.
   *
   * @param  int|string|array $categories       Category IDs.
   * @param  string           $child_categories 'no' or 'false' disables child cats.
   * @param  array            $exclude          Accepts an array of IDs.
   * @return array                              WP_Query $args array.
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

  /**
   * Used when the category is set using the `name` shortcode parameter.
   *
   * This method returns a category ID when a single category is specified,
   * a string containing comma separated category IDs when using the 'or'
   * relationship, an array of category IDs when using the 'and' relationship.
   *
   * If $name does not resolve to an existing name or slug, `0` will be returned.
   * Similarly, the returned comma separated string or an array will have `0`
   * for any name/slug that could not be found.
   *
   * @param  string $name     Accepts valid `name` shortcode parameter values.
   * @return int|string|array Int for single category, string for 'or' relationsip,
   *                          array for 'and' relationship.
   */
  public function with_name($name) {
    if (false !== strpos($name, '+')) { // AND relationship
      return $this->and_relationship($name);
    } elseif (false !== strpos($name, ',')) { // OR relationship
      return $this->or_relationship($name);
    }
    return $this->get_category_id_by_name($name);
  }

  /**
   * Used when the category is set using the `id` shortcode parameter.
   *
   * Accepts all valid `id` parameter values. If $cat_id is a single category
   * ID or comma separated IDs (for the 'or' relationship), this method does not
   * perfom any parsing and returns the string as is. If the 'and' relationship
   * is used (eg. `id=1+2+3`), returns an array of IDs. If the 'and' relationship is
   * used together with excluded categories (`id=1+2-3-4`), returns an array of
   * included IDs that also contains the 'exclude' key that is an array of excluded
   * IDs.
   *
   * @param  string $name Accepts valid `id` shortcode parameter values.
   * @return string|array Array of IDs for 'and' relationship, string otherwise.
   */
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
        $cat_id['exclude'] = explode('-', ltrim($matches['ex'], '-'));
      } else {
        // Simple 'and' relationship, just convert input into an array.
        $cat_id = array_map('intval', explode("+", $cat_id));
      }
    }
    // In all other cases leave user input as is.
    return $cat_id;
  }

  /**
   * Handles the `categorypage` shortcode parameter with all its modes.
   *
   * This method accepts all valid `categorypage` shortcode parameters.
   * Also accepts an empty string for compatibility with the widget.
   * Returns a single category ID when used on category archive page,
   * a comma separated string of IDs for the 'or' relationship,
   * an array of IDs for the 'and' relationship. When no posts should be
   * displayed it returns `[0]`.
   *
   * @param  string $mode     Accepts 'all', 'yes', 'other' and empty string.
   * @return int|string|array Category ID(s).
   */
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
   * Gets the category id from its name.
   *
   * @author Eric Celeste / http://eric.clst.org
   *
   * @param   string $category_name Accepts category name or slug.
   * @return  int                   Category ID or 0 if none found.
   */
  private function get_category_id_by_name($category_name) {
    //We check if the slug gets the category id, if not, we check the name.
    $term = get_term_by('slug', $category_name, 'category');
    if (!$term) {
      $term = get_term_by('name', $category_name, 'category');
    }
    return ($term) ? $term->term_id : 0;
  }

  /**
   * Handles 'and' relationship when categories are specified by name.
   *
   * Parses the input string and returns an array of corresponding
   * category IDs.
   *
   * @param  string $name Accepts category names or slugs separated by the '+' sign.
   * @return array        Category IDs.
   */
  private function and_relationship($name) {
    $categories = array();
    $cat_array = explode("+", $name);

    foreach ($cat_array as $category) {
      $categories[] = $this->get_category_id_by_name($category);
    }
    return $categories;
  }

  /**
   * Handles 'or' relationship when categories are specified by name.
   *
   * Parses the input string and returns comma separated
   * category IDs.
   *
   * @param  string $name Accepts category names or slugs separated by the ',' sign.
   * @return string       Comma separated category IDs.
   */
  private function or_relationship($name) {
    $categories = array();
    $catArray = explode(",", $name);

    foreach ($catArray as $category) {
      $categories[] = $this->get_category_id_by_name($category);
    }

    return implode(',',$categories);
  }

  /**
   * Handles the 'main_cat_only' shortcode parameter.
   * 
   * When filtering by main category is enabled, adds
   * a proper filter function to the 'posts_results' hook.
   * 
   * @param string $main_cat_only Shortcode parameter value, 'yes' to enable.
   * @param mixed  $categories    Category ID of the main category to filter by.
   */
  private function check_main_cat_only( $main_cat_only, $categories ) {
    if ( 'yes' === $main_cat_only ) {
      $this->main_cat = intval( $categories );
      add_filter( 'posts_results', [ $this, 'filter_by_main_category' ] );
    }
  }

  /**
   * Filter method intended for the 'posts_results' hook.
   * 
   * Filters the posts array and returns only those that
   * have their main/primary category matching the one saved
   * in the $main_cat private property.
   * 
   * @param array $posts WP_Post objects.
   * @return array       Filtered WP_Post objects.
   */
  public function filter_by_main_category( $posts ) {
    /* array_values is necessary to fix indexes, WordPress expects posts
       array to have proper numerical indexing but array_filter retains
       original array's keys.
     */
    return array_values( array_filter( $posts, function( $post ) {
      return $this->get_post_primary_category( $post )->term_id === $this->main_cat;
    }));
  }

  /**
   * Gets the main category of a post.
   * 
   * This method accepts a post ID and first tries to get the
   * primary category (a Yoast SEO feature) of the post. If none is found
   * it falls back to the first assigned category on the post's category list.
   * 
   * @link https://www.lab21.gr/blog/wordpress-get-primary-category-post/
   * 
   * @param int $post_id ID of the post to check.
   * @return mixed       Category ID (int) of the post's main category or null if none found.
   */
  private function get_post_primary_category( $post_id ) {
    $return = null;

    if ( class_exists( 'WPSEO_Primary_Term' ) ) {
      // Show Primary category by Yoast if it is enabled & set
      $wpseo_primary_term = new WPSEO_Primary_Term( 'category', $post_id );
      $primary_term = get_term( $wpseo_primary_term->get_primary_term() ) ;

      if ( !is_wp_error( $primary_term ) ) {
        $return = $primary_term;
      }
    }

    if ( empty( $return ) ) {
      $categories_list = get_the_terms( $post_id, 'category' );

      if ( !empty( $categories_list ) ) {
        $return = $categories_list[0];  //get the first category
      }
    }

    return $return;
  }
}
