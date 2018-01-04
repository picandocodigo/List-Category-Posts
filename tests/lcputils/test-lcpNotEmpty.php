<?php

class Tests_LcpUtils_LcpNotEmpty extends WP_UnitTestCase {

    private $values = array(
        /* considered as empty */
        null,
        array(),
        '',
        0,
        '0',
        /* not considered as empty */
        ' ',
        'null',
        1,
        -1,
        'some string',
        ' another string with extra spaces '
    );

    public function test_if_returns_false() {

        $utils = new LcpUtils( $this->values );

        // Empty values
        $this->assertFalse( $utils->lcp_not_empty( 0 ) );
        $this->assertFalse( $utils->lcp_not_empty( 1 ) );
        $this->assertFalse( $utils->lcp_not_empty( 2 ) );
        $this->assertFalse( $utils->lcp_not_empty( 3 ) );
        $this->assertFalse( $utils->lcp_not_empty( 4 ) );
        $this->assertFalse( $utils->lcp_not_empty( 99 ) ); /* Non-existing parameter. */
    }

    public function test_if_returns_true() {

        $utils = new LcpUtils( $this->values );

        // Not empty values
        $this->assertTrue( $utils->lcp_not_empty( 5 ) );
        $this->assertTrue( $utils->lcp_not_empty( 6 ) );
        $this->assertTrue( $utils->lcp_not_empty( 7 ) );
        $this->assertTrue( $utils->lcp_not_empty( 8 ) );
        $this->assertTrue( $utils->lcp_not_empty( 9 ) );
        $this->assertTrue( $utils->lcp_not_empty( 10 ) );
    }

}