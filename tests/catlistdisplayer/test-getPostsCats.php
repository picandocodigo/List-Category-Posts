<?php

class Tests_CatListDisplayer_GetPostsCats extends WP_UnitTestCase {

  protected static $test_post, $test_post_2, $test_cat,
    $test_cat_2, $atts;

  public static function wpSetUpBeforeClass($factory) {
    self::$test_post = $factory->post->create_and_get();

    // Test categories
    self::$test_cat = $factory->term->create(array(
      'taxonomy' => 'category',
      'name' => 'Lcp test cat'
    ));

    self::$test_cat_2 = $factory->term->create(array(
      'taxonomy' => 'category',
      'name' => 'Lcp test cat 2'
    ));

    // Post with 2 categories
    self::$test_post_2 = $factory->post->create_and_get(array(
      'post_category' => array(self::$test_cat, self::$test_cat_2)
    ));

    self::$atts = ListCategoryPosts::default_params();
  }

  public function test_should_display_posts_cats() {

    // Default value
    $displayer = new CatListDisplayer(self::$atts);
    $this->assertNull($displayer->get_posts_cats(self::$test_post));

    // Random string
    $displayer = new CatListDisplayer(array_merge(self::$atts, ['posts_cats' => 'asdfhgjkl']));
    $this->assertNull($displayer->get_posts_cats(self::$test_post));

    // Display if set to 'yes', default behaviour.
    $displayer = new CatListDisplayer(array_merge(self::$atts, ['posts_cats' => 'yes']));
    $this->assertSame(' Uncategorized', $displayer->get_posts_cats(self::$test_post));
  }

  public function test_should_display_prefix(){
    $displayer = new CatListDisplayer(array_merge(self::$atts, [
      'posts_cats' => 'yes',
      'posts_cats_prefix' => 'Prefix '
    ]));
    $this->assertSame('Prefix Uncategorized', $displayer->get_posts_cats(self::$test_post));
  }

  public function test_should_display_catlink() {
    $displayer = new CatListDisplayer(array_merge(self::$atts, [
      'posts_cats' => 'yes',
      'posts_catlink' => 'yes'
    ]));
    $actual = $displayer->get_posts_cats(self::$test_post);

    $this->assertStringContainsString('<a href="', $actual);
    $this->assertStringContainsString('">Uncategorized</a>', $actual);
  }

  public function test_display_glue () {
    $displayer = new CatListDisplayer(array_merge(self::$atts, [
      'posts_cats' => 'yes',
      'posts_cats_glue' => ' GLUE '
    ]));
    $this->assertSame(
      ' Lcp test cat GLUE Lcp test cat 2',
      $displayer->get_posts_cats(self::$test_post_2)
    );
  }

  public function test_display_inner_tag() {
    $displayer = new CatListDisplayer(array_merge(self::$atts, [
      'posts_cats' => 'yes',
      'posts_cats_inner' => 'p'
    ]));

    $expected = ' <p class="cat-uncategorized">Uncategorized</p>';
    $this->assertSame($expected, $displayer->get_posts_cats(self::$test_post));
  }
}
