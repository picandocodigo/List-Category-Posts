<?php

class Tests_CatListDisplayer_GetPagination extends WP_UnitTestCase {

  private static $atts;

  public static function wpSetUpBeforeClass($factory) {
    self::$atts = ListCategoryPosts::default_params();

    //Add some posts for pagination.
    $factory->post->create_many(20);

    // Spoof QUERY_STRING becuase URL rewriting is off
    // and lcp_page_link directly accesses it (avoid 'undefined index')
    $_SERVER['QUERY_STRING'] = '';
  }

  public function test_inactive() {
    $displayer = new CatListDisplayer(array_merge(self::$atts));
    // Run the plugin.
    $displayer->display();

    $this->assertNull($displayer->get_pagination());
  }

  public function test_active() {
    $displayer = new CatListDisplayer(
      array_merge(
        self::$atts,
        ['pagination' => 'yes']
      )
    );
    // Run the plugin.
    $displayer->display();

    // Only check if anything is returned. This is tested in detail
    // in a separate test suite.
    $this->assertIsString($displayer->get_pagination());
  }
}
