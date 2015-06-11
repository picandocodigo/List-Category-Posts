<?php

class LcpUtils_Tests extends WP_UnitTestCase {

	public function test_lcp_not_empty() {
		$values = array(
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
		$utils = new LcpUtils( $values );

		// Empty values
		$this->assertFalse( $utils->lcp_not_empty( 0 ) );
		$this->assertFalse( $utils->lcp_not_empty( 1 ) );
		$this->assertFalse( $utils->lcp_not_empty( 2 ) );
		$this->assertFalse( $utils->lcp_not_empty( 3 ) );
		$this->assertFalse( $utils->lcp_not_empty( 4 ) );
		$this->assertFalse( $utils->lcp_not_empty( 99 ) ); /* Non-existing parameter. */

		// Not empty values
		$this->assertTrue( $utils->lcp_not_empty( 5 ) );
		$this->assertTrue( $utils->lcp_not_empty( 6 ) );
		$this->assertTrue( $utils->lcp_not_empty( 7 ) );
		$this->assertTrue( $utils->lcp_not_empty( 8 ) );
		$this->assertTrue( $utils->lcp_not_empty( 9 ) );
		$this->assertTrue( $utils->lcp_not_empty( 10 ) );
	}

}
