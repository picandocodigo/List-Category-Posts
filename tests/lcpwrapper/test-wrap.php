<?php

class Tests_LcpWrapper_Wrap extends WP_UnitTestCase {

  private $test_string = 'test string';

  public function test_no_tag_no_class() {
    $wrapper = LcpWrapper::get_instance();
    $this->assertSame('test string', $wrapper->wrap($this->test_string));
  }

  public function test_tag_no_class() {
    $wrapper = LcpWrapper::get_instance();
    $this->assertSame('<div>test string</div>',
                      $wrapper->wrap($this->test_string, 'div'));
  }

  public function test_no_tag_class() {
    $wrapper = LcpWrapper::get_instance();
    // When class provided but no tag, <span> is the default tag.
    $this->assertSame('<span class="test">test string</span>',
                      $wrapper->wrap($this->test_string, null, 'test'));
  }

  public function test_tag_class() {
    $wrapper = LcpWrapper::get_instance();
    $this->assertSame('<article class="test">test string</article>',
                      $wrapper->wrap($this->test_string, 'article', 'test'));
  }

  public function test_array_as_info() {
    $wrapper = LcpWrapper::get_instance();
    $test_array = array('this', 'is', 'an', 'array');

    $this->assertSame('thisisanarray', $wrapper->wrap($test_array));
    $this->assertSame('<p>this</p><p>is</p><p>an</p><p>array</p>',
                      $wrapper->wrap($test_array, 'p'));
    $this->assertSame('<p class="test">this</p><p class="test">is</p>' .
                      '<p class="test">an</p><p class="test">array</p>',
                      $wrapper->wrap($test_array, 'p', 'test'));
  }

  public function test_multiple_classes() {
    $wrapper = LcpWrapper::get_instance();
    $this->assertSame(
      '<span class="test1 test2 test3">test string</span>',
      $wrapper->wrap($this->test_string, null, 'test1 test2 test3'));
  }
}
