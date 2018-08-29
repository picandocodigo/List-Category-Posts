<?php

class Tests_LcpUtils_LcpOrders extends WP_UnitTestCase {

    public function test_if_arrays_equal() {
        $lcp_order = array("date" => __("Date", "list-category-posts"),
                           "modified" => __("Modified Date", "list-category-posts"),
                           "title" => __("Post title", "list-category-posts"),
                           "author" => __("Author", "list-category-posts"),
                           "ID" => __("ID", "list-category-posts"),
                           "rand" => __("Random", "list-category-posts"));
    $this->assertTrue( LcpUtils::lcp_orders() === $lcp_order);
    }
}
