<?php

trait LcpDateQuery{
  // Parameters that are set to build the argument array
  function params_set(){
    return array(
      'after' => false,
      'after_year' => false,
      'after_month' => false,
      'after_day' => false,
      'before' => false,
      'before_year' => false,
      'before_month' => false,
      'before_day' => false,
    );
  }

  /*
   * Create date_query args according to https://codex.wordpress.org/Class_Reference/WP_Query#Date_Parameters
   */
  public function create_date_query_args($args, $params) {
    $date_query = array();
    // Booleans to track which subarrays should be created.
    $after = false;
    $before = false;
    $time_periods = array('before', 'after');
    $params_set = $this->set_params_values($params);

    /*
     * Build the subarrays.
     * The after parameter takes priority over after_* parameters.
     * Similarly, the before parameter takes priority over before_* parameters.
     */
    foreach ($time_periods as $period){
      if ($params_set[$period]) {
        $date_query[$period] = $params[$period];
      } else {
          if ( $params_set[$period . '_year'] )  $date_query[$period]['year']  = $params_set[$period . '_year'];
          if ( $params_set[$period . '_month'] )  $date_query[$period]['month']  = $params_set[$period . '_month'];
          if ( $params_set[$period . '_day'] )  $date_query[$period]['day']  = $params_set[$period . '_day'];
      }
    }

    if(!empty($date_query)){
      $args = array_merge($args, array('date_query' => $date_query));
    }
    return $args;
  }

  /*
   *  Check which paramaters are set save the values
   */
  private function set_params_values($params){
    $params_set = $this->params_set();
    foreach ($params_set as $key => $value){
      if ( array_key_exists($key, $params) && $params[$key] != false){
        $params_set[$key] = $params[$key];
      }
    }
    return $params_set;
  }
}
