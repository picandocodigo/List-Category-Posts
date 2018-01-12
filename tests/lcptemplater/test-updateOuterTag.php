<?php

class Tests_LcpTemplater_UpdateOuterTag extends WP_UnitTestCase {
  public function test_no_previous_tag() {
    $templater = new LcpTemplater( '' );
    $templater->update_outer_tag( 'section' );

    $this->assertSame( 'section', $templater->outer_tag );
  }

  public function test_existing_previous_tag() {
    $templater = new LcpTemplater( 'div' );
    $templater->update_outer_tag( 'section' );

    // Tag cannot be overwritten if set at instantiation.
    $this->assertSame( 'div', $templater->outer_tag );
  }
}
