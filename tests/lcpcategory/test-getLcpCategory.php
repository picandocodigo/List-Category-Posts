<?php

class Tests_LcpCategory_GetLcpCategory extends WP_UnitTestCase {

  private static $instance;
  private static $test_cat;
  private static $test_cat_2;

  public static function wpSetUpBeforeClass($factory) {

    self::$test_cat = $factory->term->create(array(
      'taxonomy' => 'category',
      'name'     => 'Dogs'
    ));
    self::$test_cat_2 = $factory->term->create(array(
      'taxonomy' => 'category',
      'name'     => 'Cats'
    ));

    // Get an instance of LcpCategory.
    self::$instance = LcpCategory::get_instance();
  }

  public function test_single_category() {

    $this->assertSame(
      ['cat' => '5'],
      self::$instance->get_lcp_category(
        [
          'id'               => '5',
          'name'             => '',
          'categorypage'     => '',
          'child_categories' => 'yes',
          'main_cat_only'    => '',
        ]
      )
    );

    $this->assertSame(
      ['cat' => self::$test_cat],
      self::$instance->get_lcp_category(
        [
          'id'               => '',
          'name'             => 'Dogs',
          'categorypage'     => '',
          'child_categories' => 'yes',
          'main_cat_only'    => '',
        ]
      )
    );
  }

  public function test_or_relationship() {

    // By id.
    $this->assertSame(
      ['cat' => '1,2,3,4'],
      self::$instance->get_lcp_category(
        [
          'id'               => '1,2,3,4',
          'name'             => '',
          'categorypage'     => '',
          'child_categories' => 'yes',
          'main_cat_only'    => '',
        ]
      )
    );

    $this->assertSame(
      ['cat' => '1,-2,-3,4'],
      self::$instance->get_lcp_category(
        [
          'id'               => '1,-2,-3,4',
          'name'             => '',
          'categorypage'     => '',
          'child_categories' => 'yes',
          'main_cat_only'    => '',
        ]
      )
    );

    // By name.
    $this->assertSame(
      ['cat' => self::$test_cat . ',' . self::$test_cat_2],
      self::$instance->get_lcp_category(
        [
          'id'               => '',
          'name'             => 'Dogs,Cats',
          'categorypage'     => '',
          'child_categories' => 'yes',
          'main_cat_only'    => '',
        ]
      )
    );
  }

  public function test_no_child_categories() {

    // By id.
    $this->assertSame(
      ['category__in' => '1,2,3,4'],
      self::$instance->get_lcp_category(
        [
          'id'               => '1,2,3,4',
          'name'             => '',
          'categorypage'     => '',
          'child_categories' => 'no',
          'main_cat_only'    => '',
        ]
      )
    );

    // By name.
    $this->assertSame(
      ['category__in' => self::$test_cat . ',' . self::$test_cat_2],
      self::$instance->get_lcp_category(
        [
          'id'               => '',
          'name'             => 'Dogs,Cats',
          'categorypage'     => '',
          'child_categories' => 'no',
          'main_cat_only'    => '',
        ]
      )
    );
  }

  public function test_and_relationship() {

    // By id.
    $this->assertSame(
      ['category__and' => [1, 2, 3, 4]],
      self::$instance->get_lcp_category(
        [
          'id'               => '1+2+3+4',
          'name'             => '',
          'categorypage'     => '',
          'child_categories' => 'yes',
          'main_cat_only'    => '',
        ]
      )
    );

    // By name.
    $this->assertSame(
      ['category__and' => [self::$test_cat, self::$test_cat_2]],
      self::$instance->get_lcp_category(
        [
          'id'               => '',
          'name'             => 'Dogs+Cats',
          'categorypage'     => '',
          'child_categories' => 'yes',
          'main_cat_only'    => '',
        ]
      )
    );
  }

  public function test_and_relationship_with_exlude() {

    $this->assertEquals(
      ['category__and' => [1, 2,], 'category__not_in' => [3, 4]],
      self::$instance->get_lcp_category(
        [
          'id'               => '1+2-3-4',
          'name'             => '',
          'categorypage'     => '',
          'child_categories' => 'yes',
          'main_cat_only'    => '',
        ]
      )
    );
  }

  public function test_current_category() {

    // Just test if shortcode param is recognised properly.
    // Detailed tests are in another test case.
    $this->assertSame(
      ['category__and' => [0]],
      self::$instance->get_lcp_category(
        [
          'categorypage'     => 'yes',
          'child_categories' => 'yes',
          'main_cat_only'    => '',
          'id'               => '',
        ]
      )
    );
    $this->assertSame(
      ['category__and' => [0]],
      self::$instance->get_lcp_category(
        [
          'categorypage'     => 'other',
          'child_categories' => 'yes',
          'main_cat_only'    => '',
        ]
      )
    );
    $this->assertSame(
      ['category__and' => [0]],
      self::$instance->get_lcp_category(
        [
          'categorypage'     => 'all',
          'child_categories' => 'yes',
          'main_cat_only'    => '',
        ]
      )
    );
  }
}
