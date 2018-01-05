<?php

class Tests_LcpUtils_LcpShowPagination extends WP_UnitTestCase {

    public function test_should_display_pagination() {

        // Empty shortcode parameter, 'false' in backend
        update_option('lcp_pagination', 'false');
        $this->assertFalse(LcpUtils::lcp_show_pagination(''));

        // Random string as parameter, 'false' in backend
        $this->assertFalse(LcpUtils::lcp_show_pagination('this is a random string'));

        // 'yes' as parameter, 'false' in backend
        $this->assertTrue(LcpUtils::lcp_show_pagination('yes'));

        // 'true' as parameter, 'false' in backend
        $this->assertTrue(LcpUtils::lcp_show_pagination('true'));

        // 'false' as parameter, 'false' in backend
        $this->assertFalse(LcpUtils::lcp_show_pagination('false'));

        // 'no' as parameter, 'false' in backend
        $this->assertFalse(LcpUtils::lcp_show_pagination('no'));

        update_option('lcp_pagination', 'true');

        // Empty shortcode parameter, 'true' in backend
        $this->assertTrue(LcpUtils::lcp_show_pagination(''));

        // Random string as parameter, 'true' in backend
        $this->assertTrue(LcpUtils::lcp_show_pagination('this is a random string'));

        // 'yes' as parameter, 'true' in backend
        $this->assertTrue(LcpUtils::lcp_show_pagination('yes'));

        // 'true' as parameter, 'true' in backend
        $this->assertTrue(LcpUtils::lcp_show_pagination('true'));

        // 'no' as parameter, 'true' in backend
        $this->assertFalse(LcpUtils::lcp_show_pagination('no'));

        // 'false' as parameter, 'true' in backend
        $this->assertFalse(LcpUtils::lcp_show_pagination('false'));
    }
}