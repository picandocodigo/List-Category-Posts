<?php

class Tests_LcpTemplater_Construct extends WP_UnitTestCase {
  public function test_instantiation() {
    $templater = new LcpTemplater( '' );

    $this->assertTrue( $templater instanceof LcpTemplater );
  }

  public function test_empty_param() {
    $templater = new LcpTemplater( '' );

    $this->assertSame(
      trailingslashit( dirname(dirname( __DIR__ )) ) . 'templates/default.php',
      $templater->get_template()
    );
    $this->assertNull( $templater->outer_tag );
    $this->assertNull( $templater->inner_tag );
  }

  public function test_ul_as_param() {
    $templater = new LcpTemplater( 'ul' );

    $this->assertSame(
      trailingslashit( dirname(dirname( __DIR__ )) ) . 'templates/default.php',
      $templater->get_template()
    );
    $this->assertSame( 'ul', $templater->outer_tag );
    $this->assertNull( $templater->inner_tag );
  }

  public function test_ol_as_param() {
    $templater = new LcpTemplater( 'ol' );

    $this->assertSame(
      trailingslashit( dirname(dirname( __DIR__ )) ) . 'templates/default.php',
      $templater->get_template()
    );
    $this->assertSame( 'ol', $templater->outer_tag );
    $this->assertNull( $templater->inner_tag );
  }

  public function test_div_as_param() {
    $templater = new LcpTemplater( 'div' );

    $this->assertSame(
      trailingslashit( dirname(dirname( __DIR__ )) ) . 'templates/default.php',
      $templater->get_template()
    );
    $this->assertSame( 'div', $templater->outer_tag );
    $this->assertSame( 'p', $templater->inner_tag );
  }

  public function test_non_existent_user_template() {
    $templater = new LcpTemplater( 'there_is_no_template_named_like_this' );

    $this->assertSame(
      trailingslashit( dirname(dirname( __DIR__ )) ) . 'templates/default.php',
      $templater->get_template()
    );
    $this->assertNull( $templater->outer_tag );
    $this->assertNull( $templater->inner_tag );
  }

  public function test_parent_theme_template() {
    $dir = get_template_directory() . '/list-category-posts';
    @mkdir( $dir );
    $this->assertDirectoryIsWritable( $dir );

    // Create fake template file.
    $tmpl_file = fopen( $dir . '/template.php', 'w' );
    fclose( $tmpl_file );
    $this->assertFileExists($dir . '/template.php');

    $templater = new LcpTemplater( 'template' );

    $this->assertSame( $dir . '/template.php', $templater->get_template() );
    $this->assertNull( $templater->outer_tag );
    $this->assertNull( $templater->inner_tag );

    // Remove the file used above.
    unlink( $dir . '/template.php' );


  }

  public function test_child_theme_template() {
    $dir = get_stylesheet_directory() . '/list-category-posts';
    @mkdir( $dir );
    $this->assertDirectoryIsWritable( $dir );

    // Create fake template file.
    $tmpl_file = fopen( $dir . '/stylesheet.php', 'w' );
    fclose( $tmpl_file );

    $templater = new LcpTemplater( 'stylesheet' );

    $this->assertSame( $dir . '/stylesheet.php', $templater->get_template() );
    $this->assertNull( $templater->outer_tag );
    $this->assertNull( $templater->inner_tag );

    // Remove the file used above.
    unlink( $dir . '/stylesheet.php' );
  }
}
