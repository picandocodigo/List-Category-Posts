<?php

class Tests_CatListDisplayer_GetNoPostsTest extends WP_UnitTestCase {

  private static $atts;

  public static function wpSetUpBeforeClass($factory) {
    self::$atts = ListCategoryPosts::default_params();
  }

  public function test_inactive() {
    $displayer = new CatListDisplayer(array_merge(self::$atts));
    // Run the plugin.
    $displayer->display();

    $this->assertNull($displayer->get_no_posts_text());
  }

  public function test_inactive_when_posts_present() {
    self::factory()->post->create();

    $displayer = new CatListDisplayer(
      array_merge(
        self::$atts,
        ['no_posts_text' => 'some text']
      )
    );
    // Run the plugin.
    $displayer->display();

    $this->assertNull($displayer->get_no_posts_text());
  }

  public function test_active() {
    $displayer = new CatListDisplayer(
      array_merge(
        self::$atts,
        ['no_posts_text' => 'some text']
      )
    );
    // Run the plugin.
    $displayer->display();

    $this->assertSame(
      'some text',
      $displayer->get_no_posts_text()
    );
  }
}
