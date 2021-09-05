<?php

class Tests_CatListDisplayer_GetConditionalTitle extends WP_UnitTestCase {

  private static $atts;

  public static function wpSetUpBeforeClass($factory) {

    self::$atts = ListCategoryPosts::default_params();
  }

  public function test_inactive() {
    $displayer = new CatListDisplayer(self::$atts);
    $displayer->display();

    $this->assertNull($displayer->get_conditional_title());
  }

  public function test_no_display_with_no_posts() {
    $displayer = new CatListDisplayer(
      array_merge(
        self::$atts,
        ['conditional_title' => 'A title']
      )
    );

    $this->assertNull($displayer->get_conditional_title());
  }

  public function test_active() {
    self::factory()->post->create_many( 3 );

    $displayer = new CatListDisplayer(
      array_merge(
        self::$atts,
        ['conditional_title' => 'A title']
      )
    );
    $displayer->display();

    $this->assertSame(
      $displayer->get_conditional_title(),
      '<h3>A title</h3>'
    );
  }
}
