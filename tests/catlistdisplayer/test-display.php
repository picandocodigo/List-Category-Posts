<?php

class Tests_CatListDisplayer_Display extends WP_UnitTestCase {

  public function test_return_type() {
    // Insert 3 random posts into the test db
    self::factory()->post->create_many(3);
    $displayer = new CatListDisplayer(ListCategoryPosts::default_params());

    $this->assertTrue(is_string($displayer->display()));
  }

  public function test_tags_and_classes_params() {
    // Parameter evaluation works exactly the same for every type
    // so this test case only checks 'comments' as an example.

    $test_post = self::factory()->post->create_and_get(array(
      'post_title' => 'Test post'
    ));
    self::factory()->comment->create_post_comments($test_post->ID);
    $params = array('comments' => 'yes', 'comments_tag' => 'p', 'comments_class' => 'test');

    $displayer = new CatListDisplayer(array_merge(ListCategoryPosts::default_params(), $params));

    $this->assertSame('<ul class="lcp_catlist" id="lcp_instance_0">' .
                      '<li><a href="http://example.org/?p=' . $test_post->ID .
                      '">Test post</a>' .
                      '<p class="test"> (1)</p></li></ul>',
                      $displayer->display());

    // Without tag or class given
    $displayer = new CatListDisplayer(array_merge(ListCategoryPosts::default_params()));

    $this->assertSame('<ul class="lcp_catlist" id="lcp_instance_0">' .
                      '<li><a href="http://example.org/?p=' . $test_post->ID .
                      '">Test post</a></li></ul>',
                      $displayer->display());
  }
}
