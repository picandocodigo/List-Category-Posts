<?php

class Tests_CatListDisplayer_GetMorelink extends WP_UnitTestCase {

  private static $atts;
  private static $test_cat;

  public static function wpSetUpBeforeClass($factory) {

    self::$atts = ListCategoryPosts::default_params();

    self::$test_cat = $factory->term->create(array(
      'taxonomy' => 'category',
      'name' => 'Lcp test cat'
    ));
  }

  public function test_morelink_active() {
    $displayer = new CatListDisplayer(
      array_merge(
        self::$atts,
        ['morelink' => 'Testing more posts', 'id' => self::$test_cat]
      )
    );
    // Run the plugin to determine the category.
    $displayer->display();

    $this->assertSame(
      '<a href="' . get_category_link(self::$test_cat) . '">Testing more posts</a>',
      $displayer->get_morelink()
    );
  }

  public function test_morelink_inactive() {
    $displayer = new CatListDisplayer(
      array_merge(
        self::$atts,
        ['id' => self::$test_cat]
      )
    );
    // Run the plugin to determine the category.
    $displayer->display();

    $this->assertNull($displayer->get_morelink());
  }
}
