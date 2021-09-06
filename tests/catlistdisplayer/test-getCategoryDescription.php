<?php

class Tests_CatListDisplayer_GetCategoryDescription extends WP_UnitTestCase {

  private static $atts;

  public static function wpSetUpBeforeClass($factory) {
    self::$atts = ListCategoryPosts::default_params();
  }

  public function test_inactive() {
    $displayer = new CatListDisplayer(array_merge(self::$atts));
    // Run the plugin.
    $displayer->display();

    $this->assertNull($displayer->get_category_description());
  }

  public function test_active() {
    $test_cat = self::factory()->term->create([
      'taxonomy' => 'category',
      'description' => 'Fancy description',
    ]);

    $displayer = new CatListDisplayer(
      array_merge(
        self::$atts,
        [
          'category_description' => 'yes',
          'id'                   => $test_cat,
        ]
      )
    );
    // Run the plugin.
    $displayer->display();

    $this->assertSame(
      'Fancy description',
      $displayer->get_category_description()
    );
  }
}
