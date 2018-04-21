<?php

class Tests_LcpParameters_GetInstance extends WP_UnitTestCase {

  public function test_if_returns_instance() {
    $parameters = LcpParameters::get_instance();
    $this->assertTrue($parameters instanceof LcpParameters);
  }

  public function test_singleton_instantiation() {
    $parameters1 = LcpParameters::get_instance();
    $parameters2 = LcpParameters::get_instance();

    $this->assertSame($parameters1, $parameters2);
  }
}