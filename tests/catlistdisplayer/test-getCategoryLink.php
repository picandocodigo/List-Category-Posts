<?php

class Tests_CatListDisplayer_GetCategoryLink extends WP_UnitTestCase {

  private static $atts;
  private static $test_cat, $test_cat_2;

  public static function wpSetUpBeforeClass($factory) {

    self::$atts = ListCategoryPosts::default_params();

    self::$test_cat = $factory->term->create(array(
      'taxonomy' => 'category',
      'name' => 'Lcp test cat'
    ));
    self::$test_cat_2 = $factory->term->create(array(
      'taxonomy' => 'category',
      'name' => 'Lcp test cat 2'
    ));
  }

  public function test_inactive() {
    $displayer = new CatListDisplayer(
      array_merge(
        self::$atts,
        ['id' => self::$test_cat]
      )
    );
    // Run the plugin to determine the category.
    $displayer->display();

    $this->assertNull($displayer->get_category_link());
  }

  public function test_no_category_selected() {
    $displayer = new CatListDisplayer(
      array_merge(
        self::$atts,
        ['catlink' => 'yes']
      )
    );
    // Run the plugin to determine the category.
    $displayer->display();

    $this->assertNull($displayer->get_category_link());
  }

  public function test_catlink_yes() {
    $displayer = new CatListDisplayer(
      array_merge(
        self::$atts,
        ['catlink' => 'yes', 'id' => self::$test_cat]
      )
    );
    // Run the plugin to determine the category.
    $displayer->display();

    $this->assertSame(
      '<strong><a href="' . get_category_link(self::$test_cat) . '">Lcp test cat</a></strong>',
      $displayer->get_category_link()
    );

    // Many categories
    $displayer = new CatListDisplayer(
      array_merge(
        self::$atts,
        [
          'catlink' => 'yes',
          'id'      => self::$test_cat . ',' . self::$test_cat_2
        ]
      )
    );
    // Run the plugin to determine the category.
    $displayer->display();

    $this->assertSame(
      '<strong><a href="' . get_category_link(self::$test_cat) . '">Lcp test cat</a>, '
      . '<a href="' . get_category_link(self::$test_cat_2) . '">Lcp test cat 2</a></strong>',
      $displayer->get_category_link()
    );
  }

  public function test_catname_yes() {
    $displayer = new CatListDisplayer(
      array_merge(
        self::$atts,
        ['catname' => 'yes', 'id' => self::$test_cat]
      )
    );
    // Run the plugin to determine the category.
    $displayer->display();

    $this->assertSame(
      '<strong>Lcp test cat</strong>',
      $displayer->get_category_link()
    );

    // Many categories
    $displayer = new CatListDisplayer(
      array_merge(
        self::$atts,
        [
          'catname' => 'yes',
          'id'      => self::$test_cat . ',' . self::$test_cat_2
        ]
      )
    );
    // Run the plugin to determine the category.
    $displayer->display();

    $this->assertSame(
      '<strong>Lcp test cat, Lcp test cat 2</strong>',
      $displayer->get_category_link()
    );
  }

  public function test_catlink_string() {
    $displayer = new CatListDisplayer(
      array_merge(
        self::$atts,
        [
          'catlink'        => 'yes',
          'catlink_string' => 'A string',
          'id'             => self::$test_cat
        ]
      )
    );
    // Run the plugin to determine the category.
    $displayer->display();

    $this->assertSame(
      '<strong><a href="' . get_category_link(self::$test_cat) . '">A string</a></strong>',
      $displayer->get_category_link()
    );
  }
}
