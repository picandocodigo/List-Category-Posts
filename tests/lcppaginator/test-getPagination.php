<?php

class Tests_LcpPaginator_GetPagination extends WP_UnitTestCase {
   
    // Spoof params lcp_page_link requires to work
    protected $test_params = array(
        'posts_count' => '6',
        'numberposts' => '2',
        'page' => 1,
        'instance' => '0',
        'next' => '>>',
        'previous' => '<<'
    );

    public static function wpSetUpBeforeClass($factory) {
        // Insert 6 random posts into the test db
        $factory->post->create_many(6);
            
        // Spoof QUERY_STRING becuase URL rewriting is off
        // and lcp_page_link directly accesses it (avoid 'undefined index')
        $_SERVER['QUERY_STRING'] = '';
    }

    public function test_pagination_string() {
        $paginator = LcpPaginator::get_instance();

        // Check first page
        $params = array_merge($this->test_params,
                              array('pagination' => 'yes'));
        $pag_string = $paginator->get_pagination($params);

        $exp_string = "<ul class='lcp_paginator'>" .
                      "<li class='lcp_currentpage'>1</li>" .
                      "<li><a href='http://example.org?lcp_page0=2#lcp_instance_0' title='2'>2</a></li>" .
                      "<li><a href='http://example.org?lcp_page0=3#lcp_instance_0' title='3'>3</a></li>" .
                      "<li><a href='http://example.org?lcp_page0=2#lcp_instance_0' title='2' class='lcp_nextlink'>>></a></li>" .
                      "</ul>";
        $this->assertSame($exp_string, $pag_string);

        // Check second page
        $params = array_merge($this->test_params,
                              array('pagination' => 'yes', 'page' => 2));

        $pag_string = $paginator->get_pagination($params);

        $exp_string = "<ul class='lcp_paginator'>" .
                      "<li><a href='http://example.org?lcp_page0=1#lcp_instance_0' title='1' class='lcp_prevlink'><<</a></li>" .
                      "<li><a href='http://example.org?lcp_page0=1#lcp_instance_0' title='1'>1</a></li>" .
                      "<li class='lcp_currentpage'>2</li>" .
                      "<li><a href='http://example.org?lcp_page0=3#lcp_instance_0' title='3'>3</a></li>" .
                      "<li><a href='http://example.org?lcp_page0=3#lcp_instance_0' title='3' class='lcp_nextlink'>>></a></li>" .
                      "</ul>";
        $this->assertSame($exp_string, $pag_string);

        // Check last page
        $params = array_merge($this->test_params,
                              array('pagination' => 'yes', 'page' => 3));

        $pag_string = $paginator->get_pagination($params);

        $exp_string = "<ul class='lcp_paginator'>" .
                      "<li><a href='http://example.org?lcp_page0=2#lcp_instance_0' title='2' class='lcp_prevlink'><<</a></li>" .
                      "<li><a href='http://example.org?lcp_page0=1#lcp_instance_0' title='1'>1</a></li>" .
                      "<li><a href='http://example.org?lcp_page0=2#lcp_instance_0' title='2'>2</a></li>" .
                      "<li class='lcp_currentpage'>3</li>" .
                      "</ul>";
        $this->assertSame($exp_string, $pag_string);
    }
}