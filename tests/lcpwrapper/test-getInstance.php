<?php

class Tests_LcpWrapper_GetInstance extends WP_UnitTestCase {

    public function test_if_returns_instance() {
        $paginator = LcpWrapper::get_instance();
        $this->assertTrue($paginator instanceof LcpWrapper);
    }

    public function test_singleton_instantiation() {
        $paginator1 = LcpWrapper::get_instance();
        $paginator2 = LcpWrapper::get_instance();

        $this->assertSame($paginator1, $paginator2);
    }
}