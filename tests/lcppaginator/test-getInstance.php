<?php

class Tests_LcpPaginator_GetInstance extends WP_UnitTestCase {

    public function test_if_returns_instance() {
        $paginator = LcpPaginator::get_instance();
        $this->assertSame(true, $paginator instanceof LcpPaginator);
    }

    public function test_singleton_instantiation() {
        $paginator1 = LcpPaginator::get_instance();
        $paginator2 = LcpPaginator::get_instance();

        $this->assertSame($paginator1, $paginator2);
    }
}