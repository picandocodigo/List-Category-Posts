<?php

class Tests_LcpPaginator_GetPagination extends WP_UnitTestCase {

  // Spoof params lcp_page_link requires to work
  protected $test_params = array(
    'posts_count' => '6',
    'numberposts' => '2',
    'page'        => 1,
    'instance'    => '0',
    'next'        => '>>',
    'previous'    => '<<',
    'bookmarks'   => '',
    'padding'     => '5',
  );

  public static function wpSetUpBeforeClass($factory) {
    // Spoof QUERY_STRING becuase URL rewriting is off
    // and lcp_page_link directly accesses it (avoid 'undefined index')
    $_SERVER['QUERY_STRING'] = '';
  }

  public function test_pagination_string() {
    $paginator = LcpPaginator::get_instance();
    $this->go_to('/');

    // Check first page
    $params = array_merge(
      $this->test_params,
      array('pagination' => 'yes')
    );

    $pag_string = $paginator->get_pagination($params);

    $exp_string = "<ul class='lcp_paginator'>" .
                  "<li class='lcp_currentpage'>1</li>" .
                  "<li><a href='http://example.org/?lcp_page0=2#lcp_instance_0' title='2'>2</a></li>" .
                  "<li><a href='http://example.org/?lcp_page0=3#lcp_instance_0' title='3'>3</a></li>" .
                  "<li><a href='http://example.org/?lcp_page0=2#lcp_instance_0' title='2' class='lcp_nextlink'>&gt;&gt;</a></li>" .
                  "</ul>";
    $this->assertSame($exp_string, $pag_string);

    // Check second page
    $params = array_merge(
      $this->test_params,
      array('pagination' => 'yes', 'page' => 2)
    );

    $pag_string = $paginator->get_pagination($params);

    $exp_string = "<ul class='lcp_paginator'>" .
                  "<li><a href='http://example.org/?lcp_page0=1#lcp_instance_0' title='1' class='lcp_prevlink'>&lt;&lt;</a></li>" .
                  "<li><a href='http://example.org/?lcp_page0=1#lcp_instance_0' title='1'>1</a></li>" .
                  "<li class='lcp_currentpage'>2</li>" .
                  "<li><a href='http://example.org/?lcp_page0=3#lcp_instance_0' title='3'>3</a></li>" .
                  "<li><a href='http://example.org/?lcp_page0=3#lcp_instance_0' title='3' class='lcp_nextlink'>&gt;&gt;</a></li>" .
                  "</ul>";
    $this->assertSame($exp_string, $pag_string);

    // Check last page
    $params = array_merge(
      $this->test_params,
      array('pagination' => 'yes', 'page' => 3)
    );

    $pag_string = $paginator->get_pagination($params);

    $exp_string = "<ul class='lcp_paginator'>" .
                  "<li><a href='http://example.org/?lcp_page0=2#lcp_instance_0' title='2' class='lcp_prevlink'>&lt;&lt;</a></li>" .
                  "<li><a href='http://example.org/?lcp_page0=1#lcp_instance_0' title='1'>1</a></li>" .
                  "<li><a href='http://example.org/?lcp_page0=2#lcp_instance_0' title='2'>2</a></li>" .
                  "<li class='lcp_currentpage'>3</li>" .
                  "</ul>";
    $this->assertSame($exp_string, $pag_string);
  }

  public function test_bookmarks() {
    $paginator = LcpPaginator::get_instance();

    $params = array_merge(
      $this->test_params,
      array('pagination' => 'yes', 'bookmarks' => 'no')
    );

    $pag_string = $paginator->get_pagination($params);

    $exp_string = "<ul class='lcp_paginator'>" .
                  "<li class='lcp_currentpage'>1</li>" .
                  "<li><a href='http://example.org/?lcp_page0=2' title='2'>2</a></li>" .
                  "<li><a href='http://example.org/?lcp_page0=3' title='3'>3</a></li>" .
                  "<li><a href='http://example.org/?lcp_page0=2' title='2' class='lcp_nextlink'>&gt;&gt;</a></li>" .
                  "</ul>";
    $this->assertSame($exp_string, $pag_string);
  }

  public function test_dynamic_pagination() {
    $paginator = LcpPaginator::get_instance();
    $this->go_to('/');

    // First page
    $params = array_merge(
      $this->test_params,
      array( 'pagination' => 'yes', 'posts_count' => 40 )
    );

    $pag_string = $paginator->get_pagination( $params );

    $exp_string = "<ul class='lcp_paginator'>" .
                  "<li class='lcp_currentpage'>1</li>" .
                  "<li><a href='http://example.org/?lcp_page0=2#lcp_instance_0' title='2'>2</a></li>" .
                  "<li><a href='http://example.org/?lcp_page0=3#lcp_instance_0' title='3'>3</a></li>" .
                  "<li><a href='http://example.org/?lcp_page0=4#lcp_instance_0' title='4'>4</a></li>" .
                  "<li><a href='http://example.org/?lcp_page0=5#lcp_instance_0' title='5'>5</a></li>" .
                  "<li><a href='http://example.org/?lcp_page0=6#lcp_instance_0' title='6'>6</a></li>" .
                  "<span class='lcp_elipsis'>...</span>" .
                  "<li><a href='http://example.org/?lcp_page0=20#lcp_instance_0' title='20'>20</a></li>" .
                  "<li><a href='http://example.org/?lcp_page0=2#lcp_instance_0' title='2' class='lcp_nextlink'>&gt;&gt;</a></li>" .
                  "</ul>";

    $this->assertSame( $exp_string, $pag_string );

    // Middle page
    $params = array_merge(
      $this->test_params,
      array( 'pagination' => 'yes', 'posts_count' => 40, 'page' => 10 )
    );

    $pag_string = $paginator->get_pagination( $params );

    $exp_string = "<ul class='lcp_paginator'>" .
                  "<li><a href='http://example.org/?lcp_page0=9#lcp_instance_0' title='9' class='lcp_prevlink'>&lt;&lt;</a></li>" .
                  "<li><a href='http://example.org/?lcp_page0=1#lcp_instance_0' title='1'>1</a></li>" .
                  "<span class='lcp_elipsis'>...</span>" .
                  "<li><a href='http://example.org/?lcp_page0=5#lcp_instance_0' title='5'>5</a></li>" .
                  "<li><a href='http://example.org/?lcp_page0=6#lcp_instance_0' title='6'>6</a></li>" .
                  "<li><a href='http://example.org/?lcp_page0=7#lcp_instance_0' title='7'>7</a></li>" .
                  "<li><a href='http://example.org/?lcp_page0=8#lcp_instance_0' title='8'>8</a></li>" .
                  "<li><a href='http://example.org/?lcp_page0=9#lcp_instance_0' title='9'>9</a></li>" .
                  "<li class='lcp_currentpage'>10</li>" .
                  "<li><a href='http://example.org/?lcp_page0=11#lcp_instance_0' title='11'>11</a></li>" .
                  "<li><a href='http://example.org/?lcp_page0=12#lcp_instance_0' title='12'>12</a></li>" .
                  "<li><a href='http://example.org/?lcp_page0=13#lcp_instance_0' title='13'>13</a></li>" .
                  "<li><a href='http://example.org/?lcp_page0=14#lcp_instance_0' title='14'>14</a></li>" .
                  "<li><a href='http://example.org/?lcp_page0=15#lcp_instance_0' title='15'>15</a></li>" .
                  "<span class='lcp_elipsis'>...</span>" .
                  "<li><a href='http://example.org/?lcp_page0=20#lcp_instance_0' title='20'>20</a></li>" .
                  "<li><a href='http://example.org/?lcp_page0=11#lcp_instance_0' title='11' class='lcp_nextlink'>&gt;&gt;</a></li>" .
                  "</ul>";

    $this->assertSame( $exp_string, $pag_string );

    // Last page
    $params = array_merge(
      $this->test_params,
      array( 'pagination' => 'yes', 'posts_count' => 40, 'page' => 20 )
    );
    $pag_string = $paginator->get_pagination( $params );

    $exp_string = "<ul class='lcp_paginator'>" .
                  "<li><a href='http://example.org/?lcp_page0=19#lcp_instance_0' title='19' class='lcp_prevlink'>&lt;&lt;</a></li>" .
                  "<li><a href='http://example.org/?lcp_page0=1#lcp_instance_0' title='1'>1</a></li>" .
                  "<span class='lcp_elipsis'>...</span>" .
                  "<li><a href='http://example.org/?lcp_page0=15#lcp_instance_0' title='15'>15</a></li>" .
                  "<li><a href='http://example.org/?lcp_page0=16#lcp_instance_0' title='16'>16</a></li>" .
                  "<li><a href='http://example.org/?lcp_page0=17#lcp_instance_0' title='17'>17</a></li>" .
                  "<li><a href='http://example.org/?lcp_page0=18#lcp_instance_0' title='18'>18</a></li>" .
                  "<li><a href='http://example.org/?lcp_page0=19#lcp_instance_0' title='19'>19</a></li>" .
                  "<li class='lcp_currentpage'>20</li>" .
                  "</ul>";

    $this->assertSame( $exp_string, $pag_string );
  }
}
