<?php
/**
 * This file defines the LcpTaxonomies trait.
 */

/**
 * The LcpTaxonomies trait is inteded to be used in
 * the LcpParameters class. It contains code that builds
 * WP_Meta_Query.
 *
 * All custom taxonomy related code should be in this trait.
 *
 * @see  WP_Tax_Query
 */
trait LcpTaxonomies {

  /**
   * Parses taxonomy related shortcode parameters.
   *
   * This is the only method of this trait that is inteded to be called
   * directly. It calls helper methods to parse shortcode parameters that
   * select posts by custom taxonomy terms and to build a WP_Tax_Query compatible
   * array of arguments. The 'tax_query' array is only appended to $args
   * if it is not empty i.e. at least one relevant shortcode parameter was used.
   *
   * @param  array $args   Array of WP_Query arguments.
   * @param  array $params Shortcode parameters.
   * @return array         The original `$args` with a new 'tax_query' array
   *                       appended if any customfield options were specified.
   */
  public function create_tax_query( $args, $params ) {
    $tax_query = array();

    $this->check_simple_taxonomies( $params, $tax_query );
    $this->check_multiple_taxonomies( $params, $tax_query );
    /*
      Display nothing when a post has no terms.
      Note that this will not prevent sticky posts
      from being shown if they match other query parameters,
      e.g. when no category is specified or a sticky post's
      category matches the one given in `id` or `name`.
      #80
    */
    $return_posts = $this->check_current_terms( $params, $tax_query );
    if ( false === $return_posts ) {
      $args['post__in'] = [0];
    }

    /*
     * If any query clauses were added to $tax_query,
     * it needs to be added to args.
     */
    if ( !empty( $tax_query ) ) {
      $args[ 'tax_query' ] = $tax_query;
    }

    return $args;
  }
  
  /**
   * Handles selecting by term(s) of a single custom taxonomy.
   * 
   * The basic usage e.g. `taxonomy="mouse" terms="mickey,minnie" is checked
   * here. Supported relationships: 'and', 'or'.
   *
   * @param  array  $params      Shortcode parameters.
   * @param  array  &$tax_query  WP_Tax_Query compatible arguments.
   */
  private function check_simple_taxonomies( $params, &$tax_query ) {
    if ( !empty( $params[ 'taxonomy' ] ) && !empty( $params[ 'terms' ] ) ) {
      if ( strpos( $params[ 'terms' ], '+' ) !== false ) {
        $terms = explode( "+", $params[ 'terms' ] );
        $operator = 'AND';
      } else {
        $terms = explode( ",", $params[ 'terms' ] );
        $operator = 'IN';
      }

      $tax_query[] = [
        'taxonomy' => $params[ 'taxonomy' ],
        'field'    => 'slug',
        'terms'    => $terms,
        'operator' => $operator
      ];
    }
  }

  /**
   * Handles selecting by term(s) of multiple custom taxonomies.
   * 
   * Parses the second, more advanced, syntax the plugin supports for
   * custom taxonomies. It supports 'or', 'and' relationships for taxonomies
   * but only supports the 'or' relationship for terms.
   *
   * @param  array  $params      Shortcode parameters.
   * @param  array  &$tax_query  WP_Tax_Query compatible arguments.
   */
  private function check_multiple_taxonomies( $params, &$tax_query ) {
    // Multiple taxonomies support in the form
    // taxonomies_or="tax1:{term1_1,term1_2};tax2:{term2_1,term2_2,term2_3}"
    // taxonomies_and="tax1:{term1_1,term1_2};tax2:{term2_1,term2_2,term2_3}"
    if ( !empty( $params[ 'taxonomies_or' ] ) ||
         !empty( $params[ 'taxonomies_and' ] ) ) {
      if ( !empty( $params[ 'taxonomies_or' ] ) ) {
        $operator = 'OR';
        $taxonomies = $params[ 'taxonomies_or' ];
      } else {
        $operator = 'AND';
        $taxonomies = $params[ 'taxonomies_and' ];
      }
      $count = preg_match_all('/([^:]+):\{([^:]+)\}(?:;|$)/im', $taxonomies, $matches, PREG_SET_ORDER, 0);
      if ( $count > 0 ) {
        $tax_query[ 'relation' ] = $operator ;
        foreach ( $matches as $match ) {
          $tax_term = [
            'taxonomy' => $match[1],
            'field'    => 'slug',
            'terms'    => explode( ',', $match[2] )
          ];
          
          $tax_query[] = $tax_term;
        }
      }
    }
  }

  /**
   * Handles selecting by current post's custom taxonomy term(s).
   * 
   * This is analogous to the current implementation of currenttags.
   * In future refactorings the post_tag related code should be moved
   * to this trait. Unlike other 'check_' methods of this trait, this one
   * has a return value. It should return false when currentterms was
   * specified but current post had none matching. The returned value
   * is checked by 'create_tax_query'.
   *
   * @param  array  $params      Shortcode parameters.
   * @param  array  &$tax_query  WP_Tax_Query compatible arguments.
   * @return bool                True if current terms were foud, false otherwise.
   */
  private function check_current_terms( $params, &$tax_query ) {
    $currentterms = $params[ 'currentterms' ];
    if ( $currentterms === 'yes' || $currentterms === 'all' ) {
      $terms = $this->get_current_terms( $params[ 'taxonomy' ] );

      if ( !empty( $terms ) ) {
        // OR relationship
        if ( 'yes' === $currentterms ) {
          $operator = 'IN';
        } else {
          // AND relationship
          $operator = 'AND';
        }
      } else {
        return false;
      }

      $tax_query[] = [
        'taxonomy' => $params[ 'taxonomy' ],
        'field'    => 'term_id',
        'terms'    => $terms,
        'operator' => $operator
      ];
      return true;
    }
  }

  /**
   * A simple helper to get current post's custom taxonomy terms.
   *
   * @param  string  $taxonomy  Selected custom taxonomy.
   * @return array              IDs of current post's terms belonging to $taxonomy.
   * 
   * @see get_the_terms
   */
  private function get_current_terms( $taxonomy ) {
    $terms = get_the_terms( 0, $taxonomy );
    $term_ids = array();
    if( !empty( $terms ) ) {
      foreach ( $terms as $term ) {
        array_push( $term_ids, $term->term_id );
      }
    }
    return $term_ids;
  }
}
