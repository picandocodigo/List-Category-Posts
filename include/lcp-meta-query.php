<?php
/**
 * This file defines the LcpMetaQuery trait.
 */

/**
 * The LcpMetaQuery trait is inteded to be used in
 * the LcpParameters class. It contains code that builds
 * WP_Meta_Query.
 *
 * All customfield related code should be in this trait (except
 * customfield display).
 *
 * @see  WP_Meta_Query
 */
trait LcpMetaQuery {

  /**
   * Parses customfield related shortcode parameters.
   *
   * This is the only method of this trait that is inteded to be called
   * directly. It calls helper methods to parse shortcode parameters that
   * select posts by custom fields and to build a WP_Meta_Query compatible
   * array of arguments. The 'meta_query' array is only appended to $args
   * if it is not empty i.e. at least one relevant shortcode parameter was used.
   *
   * @param  array $args   Array of WP_Query arguments.
   * @param  array $params Shortcode parameters.
   * @return array         The original `$args` with a new 'meta_query' array
   *                       appended if any customfield options were specified.
   */
  public function create_meta_query_args($args, $params) {
    $meta_query = array();
    /*
     * 'AND' is the default relation, keeping this line
     * for better readability.
     */
    $meta_query['relation'] = 'AND';

    $this->check_simple_customfield($params, $meta_query);
    $this->check_customfield_orderby($params, $meta_query);
    $this->check_customfield_compare($params, $meta_query);

    /*
     * If any query clauses were added to $meta_query,
     * it needs to be added to args.
     */
    if ( !empty($meta_query) ) {
      $args['meta_query'] = $meta_query;
      // Adjust args for customfield_orderby if necessary.
      if ( isset($meta_query['orderby_clause']) ) {
        $args['orderby'] = 'orderby_clause';
      }
    }

    return $args;
  }

  /**
   * Handles the customfield_name and customfield_value shortcode parameters.
   *
   * @param  array  $params      Shortcode parameters.
   * @param  array  &$meta_query WP_Meta_Query compatible arguments.
   */
  private function check_simple_customfield($params, &$meta_query) {
    /*
     * 'customfield_name' & 'customfield_value'
     * should both be defined
     */
    if( $params['customfield_name'] ) {
      $meta_query['select_clause'] = array(
        'key'   => $params['customfield_name'],
        'value' => $params['customfield_value'],
      );
    }
  }

  /**
   * Handles the customfield_orderby shortcode parameter.
   *
   * @param  array  $params      Shortcode parameters.
   * @param  array  &$meta_query WP_Meta_Query compatible arguments.
   */
  private function check_customfield_orderby($params, &$meta_query) {
    if ( $params['customfield_orderby'] ) {

	  $meta_query['orderby_clause'] = array(
        'key' => $params['customfield_orderby'],
        'compare' => 'EXISTS',
      );

      if( !empty($params['customfield_type']) ) {
        $meta_query['orderby_clause']['type'] = strtoupper($params['customfield_type']);
      }
    }
  }

  /**
   * Handles the customfield_compare shortcode parameter.
   *
   * @param  array  $params      Shortcode parameters.
   * @param  array  &$meta_query WP_Meta_Query compatible arguments.
   */
  private function check_customfield_compare($params, &$meta_query) {
    if ($params['customfield_compare']) {

      // customfield_compare=key,compare,value,type;key,compare,value,type...
      $compare_queries = explode(';', $params['customfield_compare']);

      /*
       * Start building a nested array query.
       * 'AND' is the default relation. Arrays with user defined queries
       * will be appended to $compare_clauses.
       */
      $compare_clauses = ['relation' => 'AND'];

      foreach ($compare_queries as $query) {
        // Get an array in the format: [key,compare,value,type].
        $compare_args = explode(',', $query);
        $compare_clause = [];

        // Determine type first because it is needed for $format_value below.
        if (isset($compare_args[3])) {
          // If not set, defaults to 'CHAR' (as per WP docs).
          $compare_clause['type'] = strtoupper($compare_args[3]);
        } else {
          $compare_clause['type'] = 'CHAR';
        }

        /*
         * Prepare a value formatter to use in customfield comparisons.
         * this returns a function that takes field value as an argument.
         */
        $format_value = call_user_func(
          'LcpUtils::lcp_format_customfield',
          $compare_clause['type']
        );

        $compare_clause['key']     = $compare_args[0];
        // If not set, defaults to '=' but in this implementation we make it required.
        $compare_clause['compare'] = $this->customfield_compare_convert($compare_args[1]);
        // value is not required and should not be used for the EXISTS comparison.
        if (isset($compare_args[2])) {
          $compare_clause['value'] = $format_value($compare_args[2]);
        }

        $compare_clauses[] = $compare_clause;
      }

      $meta_query[] = $compare_clauses;
    }
  }

  /**
   * Converts user input to WP_Meta_Query compatible values.
   *
   * Symbols like `<` and any other HTML are not allowed in shortcodes.
   * This is why we must provide users with simple text values they can
   * use and then convert them to what WP_Meta_Query needs.
   *
   * @link https://codex.wordpress.org/Shortcode_API#HTML
   *
   * @param  string $value   Value to be converted.
   * @return string          Converted output or initial input
   *                         if no match is found.
   */
  private function customfield_compare_convert($value) {
    $conversion_table = [
      'greaterthan'      => '>',
      'greaterthanequal' => '>=',
      'lessthan'         => '<',
      'lessthanequal'    => '<=',
      'equals'           => '=',
      'not_equals'       => '!=',
      // This is just so that users do not have to use spaces.
      'not_exists'       => 'NOT EXISTS',
    ];
    if (isset($conversion_table[$value])) {
      return $conversion_table[$value];
    } else {
      return $value;
    }
  }
}
