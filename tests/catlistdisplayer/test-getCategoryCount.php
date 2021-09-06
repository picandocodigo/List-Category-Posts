<?php

class Tests_CatListDisplayer_GetCategoryCount extends WP_UnitTestCase {

  private static $atts;

  public static function wpSetUpBeforeClass($factory) {
    self::$atts = ListCategoryPosts::default_params();
  }

  public function test_inactive() {
    $displayer = new CatListDisplayer(array_merge(self::$atts));
    // Run the plugin.
    $displayer->display();

    $this->assertNull($displayer->get_category_count());
  }

  public function test_active() {
    $test_cat = self::factory()->term->create([
      'taxonomy' => 'category',
    ]);

    self::factory()->post->create_many(5, [
      'post_category' => [$test_cat]
    ]);

    $displayer = new CatListDisplayer(
      array_merge(
        self::$atts,
        [
          'category_count' => 'yes',
          'id'             => $test_cat,
        ]
      )
    );
    // Run the plugin.
    $displayer->display();

    $this->assertSame(
      ' 5',
      $displayer->get_category_count()
    );
  }
}
