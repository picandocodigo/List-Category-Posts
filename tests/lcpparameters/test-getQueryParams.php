<?php

class Tests_LcpParameters_GetQueryParams extends WP_UnitTestCase {
  private static $lcp_parameters;
  private static $default_params;

  public static function wpSetUpBeforeClass($factory) {
    self::$lcp_parameters = LcpParameters::get_instance();
    self::$default_params = ListCategoryPosts::default_params();
  }

  public function test_ignore_sticky_posts_disabled() {
    $this->assertArrayNotHasKey(
      'ignore_sticky_posts',
      self::$lcp_parameters->get_query_params(self::$default_params)
    );
  }

  public function test_ignore_sticky_posts() {
    $params = array_merge(
      self::$default_params,
      ['ignore_sticky_posts' => 'yes']
    );
    
    $this->assertArrayHasKey(
      'ignore_sticky_posts',
      self::$lcp_parameters->get_query_params($params)
    );

    $this->assertSame(
      true,
      self::$lcp_parameters->get_query_params($params)['ignore_sticky_posts']
    );
  }
}
