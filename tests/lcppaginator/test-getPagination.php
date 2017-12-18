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

    public function test_should_display_pagination() {
        $paginator = LcpPaginator::get_instance();

        // Empty shortcode parameter, 'false' in backend
        update_option('lcp_pagination', 'false');
        $params = array('pagination' => '');
        $this->assertSame(null, $paginator->get_pagination($params));

        // Random string as parameter, 'false' in backend
        $params = array('pagination' => 'this is a random string');
        $this->assertSame(null, $paginator->get_pagination($params));

        // 'yes' as parameter, 'false' in backend
        $params = array_merge($this->test_params,
                              array('pagination' => 'yes'));
        $this->assertTrue(is_string($paginator->get_pagination($params)));

        // 'true' as parameter, 'false' in backend
        $params = array_merge($this->test_params,
                              array('pagination' => 'true'));
        $this->assertTrue(is_string($paginator->get_pagination($params)));

        // 'false' as parameter, 'false' in backend
        $params = array('pagination' => 'false');
        $this->assertSame(null, $paginator->get_pagination($params));

        // 'no' as parameter, 'false' in backend
        $params = array('pagination' => 'no');
        $this->assertSame(null, $paginator->get_pagination($params));

        update_option('lcp_pagination', 'true');

        // Empty shortcode parameter, 'true' in backend
        $params = array_merge($this->test_params,
                              array('pagination' => ''));
        $this->assertTrue(is_string($paginator->get_pagination($params)));

        // Random string as parameter, 'true' in backend
        $params = array_merge($this->test_params,
                              array('pagination' => 'this is a random string'));
        $this->assertTrue(is_string($paginator->get_pagination($params)));

        // 'yes' as parameter, 'true' in backend
        $params = array_merge($this->test_params,
                              array('pagination' => 'yes'));
        $this->assertTrue(is_string($paginator->get_pagination($params)));

        // 'true' as parameter, 'true' in backend
        $params = array_merge($this->test_params,
                              array('pagination' => 'true'));
        $this->assertTrue(is_string($paginator->get_pagination($params)));

        // 'no' as parameter, 'false' in backend
        $params = array('pagination' => 'no');
        $this->assertSame(null, $paginator->get_pagination($params));

        // 'false' as parameter, 'true' in backend
        $params = array('pagination' => 'false');
        $this->assertSame(null, $paginator->get_pagination($params));
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