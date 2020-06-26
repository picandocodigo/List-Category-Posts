<?php

class Tests_LcpCategory_WithId extends WP_UnitTestCase {

  private static $instance;

  public static function wpSetUpBeforeClass($factory) {

    // Get an instance of LcpCategory.
    self::$instance = LcpCategory::get_instance();
  }

  public function test_single_id() {

    // It should just return the argument as is.
    $this->assertSame(
      53,
      self::$instance->with_id(53)
    );
    $this->assertSame(
      '432',
      self::$instance->with_id('432')
    );
  }

  public function test_and_relationship() {

    // 2 categories by id.
    $this->assertSame(
      [1, 2],
      self::$instance->with_id('1+2')
    );
    // 3 categories by id.
    $this->assertSame(
      [1, 2, 3],
      self::$instance->with_id('1+2+3')
    );
  }

  public function test_or_relationship() {

    // It should just return the argument as is.
    $this->assertSame(
      '1,2,3,4',
      self::$instance->with_id('1,2,3,4')
    );
  }

  public function test_exclude() {

    // It should just return the argument as is.
    $this->assertSame(
      '1,-2,-3,4',
      self::$instance->with_id('1,-2,-3,4')
    );
  }

  public function test_and_relationship_with_exclude() {

    // It should just return the argument as is.
    $this->assertEquals(
      [1, 2, 3, 'exclude' => [5, 6]],
      self::$instance->with_id('1+2+3-5-6')
    );
  }
}
