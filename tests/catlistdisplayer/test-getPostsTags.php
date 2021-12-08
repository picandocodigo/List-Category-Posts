<?php

class Tests_CatListDisplayer_GetPostsTags extends WP_UnitTestCase {

  protected static $test_post, $atts;

  public static function wpSetUpBeforeClass($factory) {
    self::$test_post = $factory->post->create_and_get([
      'tags_input' => array('Tag1', 'Tag2', 'Tag3'),
    ]);

    self::$atts = ListCategoryPosts::default_params();
  }

  public function test_should_display_posts_tags() {

    // Default value
    $displayer = new CatListDisplayer(self::$atts);
    $this->assertNull($displayer->get_posts_tags(self::$test_post));

    // Random string
    $displayer = new CatListDisplayer(array_merge(self::$atts, ['posts_tags' => 'asdfhgjkl']));
    $this->assertNull($displayer->get_posts_tags(self::$test_post));

    // Display if set to 'yes', default behaviour.
    $displayer = new CatListDisplayer(array_merge(self::$atts, ['posts_tags' => 'yes']));
    $this->assertSame(' Tag1, Tag2, Tag3', $displayer->get_posts_tags(self::$test_post));

    // Post without any tags
    $no_tags_post = self::factory()->post->create_and_get();
    $this->assertNull($displayer->get_posts_tags($no_tags_post));
  }

  public function test_should_display_prefix(){
    $displayer = new CatListDisplayer(array_merge(self::$atts, [
      'posts_tags' => 'yes',
      'posts_tags_prefix' => ''
    ]));
    $this->assertSame('Tag1, Tag2, Tag3', $displayer->get_posts_tags(self::$test_post));

    $displayer = new CatListDisplayer(array_merge(self::$atts, [
      'posts_tags' => 'yes',
      'posts_tags_prefix' => 'Prefix '
    ]));
    $this->assertSame('Prefix Tag1, Tag2, Tag3', $displayer->get_posts_tags(self::$test_post));
  }

  public function test_should_display_taglink() {
    $displayer = new CatListDisplayer(array_merge(self::$atts, [
      'posts_tags' => 'yes',
      'posts_taglink' => ''
    ]));
    $this->assertSame(' Tag1, Tag2, Tag3', $displayer->get_posts_tags(self::$test_post));

    $displayer = new CatListDisplayer(array_merge(self::$atts, [
      'posts_tags' => 'yes',
      'posts_taglink' => 'yes'
    ]));
    $actual = $displayer->get_posts_tags(self::$test_post);

    $this->assertStringContainsString('<a href="', $actual);
    $this->assertStringContainsString('">Tag1</a>, <a href="', $actual);
    $this->assertStringContainsString('">Tag2</a>, <a href="', $actual);
    $this->assertStringContainsString('">Tag3</a>', $actual);
  }

  public function test_display_glue () {
    $displayer = new CatListDisplayer(array_merge(self::$atts, [
      'posts_tags' => 'yes',
      'posts_tags_glue' => ''
    ]));
    $this->assertSame(' Tag1Tag2Tag3', $displayer->get_posts_tags(self::$test_post));

    $displayer = new CatListDisplayer(array_merge(self::$atts, [
      'posts_tags' => 'yes',
      'posts_tags_glue' => ' GLUE '
    ]));
    $this->assertSame(
      ' Tag1 GLUE Tag2 GLUE Tag3',
      $displayer->get_posts_tags(self::$test_post)
    );
  }

  public function test_display_inner_tag() {
    $displayer = new CatListDisplayer(array_merge(self::$atts, [
      'posts_tags' => 'yes',
      'posts_tags_inner' => ''
    ]));
    $this->assertSame(' Tag1, Tag2, Tag3', $displayer->get_posts_tags(self::$test_post));

    $displayer = new CatListDisplayer(array_merge(self::$atts, [
      'posts_tags' => 'yes',
      'posts_tags_inner' => 'p'
    ]));

    $expected = ' <p class="tag-tag1">Tag1</p>, ' .
      '<p class="tag-tag2">Tag2</p>, <p class="tag-tag3">Tag3</p>';
    $this->assertSame($expected, $displayer->get_posts_tags(self::$test_post));
  }
}
