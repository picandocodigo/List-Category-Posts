<?php

class Tests_LcpUtils_LcpFormatCustomfield extends WP_UnitTestCase {
  public function test_if_method_exists() {
    $this->assertTrue(method_exists(
      'LcpUtils',
      'lcp_format_customfield'
    ));
  }

  public function test_if_returns_a_function() {
    $this->assertTrue(is_callable(LcpUtils::lcp_format_customfield('')));
  }

  public function test_returned_function() {
    $func = LcpUtils::lcp_format_customfield('some string');
    $this->assertSame('another string', $func('another string'));

    $func = LcpUtils::lcp_format_customfield('DATETIME');
    $this->assertSame(
      date('c', strtotime('24 May 2000 09:30')),
      $func('24 May 2000 09:30')
    );

    $func = LcpUtils::lcp_format_customfield('DATE');
    $this->assertSame(
      date('Y-m-d', strtotime('24 May 2000')),
      $func('24 May 2000')
    );

    $func = LcpUtils::lcp_format_customfield('TIME');
    $this->assertSame(
      date('H:i:s', strtotime('24:00:00')),
      $func('24:00:00')
    );
  }
}