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

  public function create_tax_query( $args, $params ) {
    $tax_query = array();

    $this->check_simple_taxonomies( $params, $tax_query );
    $this->check_multiple_taxonomies( $params, $tax_query );

    /*
     * If any query clauses were added to $tax_query,
     * it needs to be added to args.
     */
    if ( !empty( $tax_query) ) {
      $args[ 'tax_query' ] = $tax_query;
    }

    return $args;
  }
  
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
}
