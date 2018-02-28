<?php

class Tests_LcpThumbnail_GetInstance extends WP_UnitTestCase {

  public function test_if_returns_instance() {
    $thumbnail = LcpThumbnail::get_instance();
    $this->assertTrue($thumbnail instanceof LcpThumbnail);
  }

  public function test_singleton_instantiation() {
    $thumbnail1 = LcpThumbnail::get_instance();
    $thumbnail2 = LcpThumbnail::get_instance();

    $this->assertSame($thumbnail1, $thumbnail2);
  }
}