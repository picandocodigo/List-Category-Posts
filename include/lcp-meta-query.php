<?php

trait LcpMetaQuery {

  function create_meta_query_args($args, $params) {
    $meta_query = array();

    /*
     * Custom fields 'customfield_name' & 'customfield_value'
     * should both be defined
     */
    if( $this->utils->lcp_not_empty('customfield_name') ) {
      $meta_query['select_clause'] = array(
        'key'   => $params['customfield_name'],
        'value' => $params['customfield_value'],
      );
    }

    if ($this->utils->lcp_not_empty('customfield_compare')) {
      $customfield_compare = $params['customfield_compare'];

      // customfield_compare=key,compare,value,type

      $customfield_compare_params = explode(',', $customfield_compare);

      // 'AND' is the default relation, keeping this line
      // for better readability.
      $compare_clause = ['relation' => 'AND'];

      $compare_clause_1['key']     = $customfield_compare_params[0];
      // If not set, defaults to '=' but in this implementation we make it required.
      $compare_clause_1['compare']   = $customfield_compare_params[1];
      if (isset($customfield_compare_params[2])) {
        $compare_clause_1['value'] = $customfield_compare_params[2];
      }
      if (isset($customfield_compare_params[3])) {
        // If not set, defaults to 'CHAR'.
        $compare_clause_1['type'] = strtoupper($customfield_compare_params[3]);
      } else {
        $compare_clause_1['type'] = 'CHAR';
      }

      // Prepare a value formatter to use in customfield comparisons.
      // this returns a function that takes field value as an argument.
      $format_value = call_user_func(
        'LcpUtils::lcp_format_customfield',
        $compare_clause_1['type']
      );
      $compare_clause[] = $compare_clause_1;

      // 'AND' is the default relation, keeping this line
      // for better readability.
      $meta_query['relation'] = 'AND';
      $meta_query[] = $compare_clause;
    }

    if ( $this->utils->lcp_not_empty('customfield_orderby') ){
      $meta_query['orderby_clause'] = array(
        'key' => $params['customfield_orderby'],
        'compare' => 'EXISTS',
      );
      $args['orderby'] = 'orderby_clause';
    }

    // If either select_clause or orderby_clause were added to $meta_query,
    // it needs to be added to args.
    if ( !empty($meta_query) ) {
      $args['meta_query'] = $meta_query;
    }

    return $args;
  }
}
