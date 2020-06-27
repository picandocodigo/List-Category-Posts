<?php

class Tests_CatList_GetOuterTag extends WP_UnitTestCase {

  public function test_default_output() {
    // Create an instance with required params.
    $catlist = new CatList(
      [
        'class'       => '',
        'instance'    => '0',
        'numberposts' => '',
      ]
    );

    $this->assertSame(
      '<ul class="lcp_catlist" id="lcp_instance_0">',
      $catlist->get_outer_tag('ul', 'lcp_catlist')
    );
  }

  public function test_arbitrary_tag() {
    // Create an instance with required params.
    $catlist = new CatList(
      [
        'class'       => '',
        'instance'    => '0',
        'numberposts' => '',
      ]
    );

    $this->assertSame(
      '<section class="lcp_catlist" id="lcp_instance_0">',
      $catlist->get_outer_tag('section', 'lcp_catlist')
    );
  }

  public function test_arbitrary_class() {
    // Create an instance with required params.
    $catlist = new CatList(
      [
        'class'       => '',
        'instance'    => '0',
        'numberposts' => '',
      ]
    );

    $this->assertSame(
      '<ul class="some-random-css-class" id="lcp_instance_0">',
      $catlist->get_outer_tag('ul', 'some-random-css-class')
    );
  }

  public function test_arbitrary_instance() {
    // Create an instance with required params.
    $catlist = new CatList(
      [
        'class'       => '',
        'instance'    => 'hey-im-an-instance',
        'numberposts' => '',
      ]
    );

    $this->assertSame(
      '<ul class="lcp_catlist" id="lcp_instance_hey-im-an-instance">',
      $catlist->get_outer_tag('ul', 'lcp_catlist')
    );
  }

  public function test_shortcode_param_priority() {
    // Create an instance with required params.
    $catlist = new CatList(
      [
        'class'       => 'my-random-class',
        'instance'    => '0',
        'numberposts' => '',
      ]
    );

    /**
     * Shortcode 'class' param should take precedence over arguments passed
     * to the method being tested here.
     */
    $this->assertSame(
      '<ul class="my-random-class" id="lcp_instance_0">',
      $catlist->get_outer_tag('ul', 'this-should-be-ignored')
    );
  }

  public function test_ol_pagination() {
    // Create an instance with required params.
    $catlist = new CatList(
      [
        'class'       => '',
        'instance'    => '0',
        'numberposts' => '10',
      ]
    );

    $catlist->update_page(2);
    $this->assertSame(
      '<ol start="11" class="lcp_catlist" id="lcp_instance_0">',
      $catlist->get_outer_tag('ol', 'lcp_catlist')
    );

    $catlist->update_page(99);
    $this->assertSame(
      '<ol start="981" class="lcp_catlist" id="lcp_instance_0">',
      $catlist->get_outer_tag('ol', 'lcp_catlist')
    );
  }

  public function test_ol_offset() {
    // Create an instance with required params.
    $catlist = new CatList(
      [
        'class'       => '',
        'instance'    => '0',
        'numberposts' => '10',
        'ol_offset'   => '4',
      ]
    );

    // Make sure it's ignored when $tag !== 'ol'.
    $this->assertSame(
      '<div class="lcp_catlist" id="lcp_instance_0">',
      $catlist->get_outer_tag('div', 'lcp_catlist')
    );

    // Check proper usage.
    $this->assertSame(
      '<ol start="4" class="lcp_catlist" id="lcp_instance_0">',
      $catlist->get_outer_tag('ol', 'lcp_catlist')
    );
  }
}
