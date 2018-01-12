<?php

class Tests_LcpTemplater_UpdateInnerTag extends WP_UnitTestCase {
  public function test_no_previous_tag() {
    $templater = new LcpTemplater( '' );
    $templater->update_inner_tag( 'p' );

    $this->assertSame( 'p', $templater->inner_tag );
  }

  public function test_existing_previous_tag() {
    $templater = new LcpTemplater( 'div' );
    $templater->update_inner_tag( 'div' );

    // Tag cannot be overwritten if set at instantiation.
    $this->assertSame( 'p', $templater->inner_tag );
  }
}
