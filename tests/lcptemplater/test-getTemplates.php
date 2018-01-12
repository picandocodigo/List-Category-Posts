<?php

class Tests_LcpTemplater_GetTemplates extends WP_UnitTestCase {
  public function test_known_templates() {
    $dir = get_stylesheet_directory() . '/list-category-posts';
    @mkdir( $dir );
    $this->assertDirectoryIsWritable( $dir );

    // Create fake template fils.
    $tmpl_file = fopen( $dir . '/template1.php', 'w' );
    fclose( $tmpl_file );
    $tmpl_file = fopen( $dir . '/template2.php', 'w' );
    fclose( $tmpl_file );

    $this->assertContains( 'template1', LcpTemplater::get_templates() );
    $this->assertContains( 'template2', LcpTemplater::get_templates() );

    // Remove the files used above.
    unlink( $dir . '/template1.php' );
    unlink( $dir . '/template2.php' );
  }
}
