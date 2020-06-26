<?php

class Tests_LcpCategory_WithName extends WP_UnitTestCase {

  private static $test_cat;
  private static $test_cat_2;
  private static $test_cat_3;

  private static $instance;

  public static function wpSetUpBeforeClass($factory) {

    // Create test categories.
    self::$test_cat = $factory->term->create(array(
      'taxonomy' => 'category',
      'name'     => 'Lcp test cat',
      'slug'     => 'lcptest',
    ));
    self::$test_cat_2 = $factory->term->create(array(
      'taxonomy' => 'category',
      'name'     => 'Lcp test cat 2',
      'slug'     => 'lcptest2',
    ));
    self::$test_cat_3 = $factory->term->create(array(
      'taxonomy' => 'category',
      'name'     => 'Lcp test cat 3',
      'slug'     => 'lcptest3',
    ));

    // Get an instance of LcpCategory.
    self::$instance = LcpCategory::get_instance();
  }

  public function test_single_name() {

    // Using a name.
    $this->assertSame(
      self::$test_cat,
      self::$instance->with_name('Lcp test cat')
    );
    // Using a slug.
    $this->assertSame(
      self::$test_cat,
      self::$instance->with_name('lcptest')
    );
    // Non existent category.
    $this->assertSame(
      0,
      self::$instance->with_name('No such category')
    );
  }

  public function test_and_relationship() {

    // 2 categories by name.
    $this->assertSame(
      [self::$test_cat, self::$test_cat_2],
      self::$instance->with_name('Lcp test cat+Lcp test cat 2')
    );
    // 3 categories by name.
    $this->assertSame(
      [self::$test_cat, self::$test_cat_2, self::$test_cat_3],
      self::$instance->with_name('Lcp test cat+Lcp test cat 2+Lcp test cat 3')
    );
    // 2 categories by slug.
    $this->assertSame(
      [self::$test_cat, self::$test_cat_2],
      self::$instance->with_name('lcptest+lcptest2')
    );
    // 3 categories by slug.
    $this->assertSame(
      [self::$test_cat, self::$test_cat_2, self::$test_cat_3],
      self::$instance->with_name('lcptest+lcptest2+lcptest3')
    );
  }

  public function test_or_relationship() {

    // 2 categories by name.
    $this->assertSame(
      self::$test_cat . ',' . self::$test_cat_2,
      self::$instance->with_name('Lcp test cat,Lcp test cat 2')
    );
    // 3 categories by name.
    $this->assertSame(
      self::$test_cat . ',' . self::$test_cat_2 . ',' . self::$test_cat_3,
      self::$instance->with_name('Lcp test cat,Lcp test cat 2,Lcp test cat 3')
    );
    // 2 categories by slug.
    $this->assertSame(
      self::$test_cat . ',' . self::$test_cat_2,
      self::$instance->with_name('lcptest,lcptest2')
    );
    // 3 categories by slug.
    $this->assertSame(
      self::$test_cat . ',' . self::$test_cat_2 . ',' . self::$test_cat_3,
      self::$instance->with_name('lcptest,lcptest2,lcptest3')
    );
  }
}
