<?php

class Tests_CatListDisplayer_GetPostTitle extends WP_UnitTestCase {

  private static $atts = [
    'title_tag'      => '',
    'title_class'    => '',
    'link_titles'    => '',
    'link_current'   => '',
    'title_limit'    => '',
    'post_suffix'    => '',
    'no_post_titles' => '',
    'template'       => '',
  ];
  private static $test_post;
  private static $displayer_default;

  public static function wpSetUpBeforeClass($factory) {

    self::$test_post = $factory->post->create_and_get(array(
      'post_title' => 'Lcp test post',
    ));

    self::$displayer_default = new CatListDisplayer(
      array_merge(self::$atts)
    );
  }

  public function test_tag_without_class() {

    $displayer = new CatListDisplayer(
      array_merge(self::$atts, ['title_tag' => 'h1'])
    );

    $expected = '<h1><a href="' . get_permalink(self::$test_post)
                . '">Lcp test post</a></h1>';
    $expected2 = '<h2><a href="' . get_permalink(self::$test_post)
                 . '">Lcp test post</a></h2>';


    // Shortcode param.
    $this->assertSame(
      $expected,
      $displayer->get_post_title(self::$test_post)
    );
    // Function argument.
    $this->assertSame(
      $expected2,
      self::$displayer_default->get_post_title(self::$test_post, 'h2')
    );
  }

  public function test_class_without_tag() {

    $displayer = new CatListDisplayer(
      array_merge(self::$atts, ['title_class' => 'my-class'])
    );

    $expected = '<a href="' . get_permalink(self::$test_post) .'"'
                . ' class="my-class">Lcp test post</a>';

    // Shortcode param.
    $this->assertSame(
      $expected,
      $displayer->get_post_title(self::$test_post)
    );
    // Function argument.
    $this->assertSame(
      $expected,
      self::$displayer_default->get_post_title(self::$test_post, null, 'my-class')
    );
  }

  public function test_class_and_tag() {

    $displayer = new CatListDisplayer(
      array_merge(
        self::$atts,
        ['title_class' => 'my-class', 'title_tag' => 'h2']
      )
    );

    $expected = '<h2 class="my-class">'
                . '<a href="' . get_permalink(self::$test_post) .'"'
                . '>Lcp test post</a></h2>';

    // Shortcode params.
    $this->assertSame(
      $expected,
      $displayer->get_post_title(self::$test_post)
    );
    // Function arguments.
    $this->assertSame(
      $expected,
      self::$displayer_default->get_post_title(
        self::$test_post, 'h2', 'my-class'
      )
    );
  }

  public function test_no_link_title() {

    $displayer = new CatListDisplayer(
      array_merge(self::$atts, ['link_titles' => 'false'])
    );
    $displayer2 = new CatListDisplayer(
      array_merge(self::$atts, ['link_titles' => 'no'])
    );

    $this->assertSame(
      'Lcp test post',
      $displayer->get_post_title(self::$test_post)
    );
    $this->assertSame(
      'Lcp test post',
      $displayer2->get_post_title(self::$test_post)
    );
    $this->assertSame(
      'Lcp test post',
      self::$displayer_default->get_post_title(
        self::$test_post, null, null, false
      )
    );
  }

  public function test_no_link_title_with_tag() {

    $displayer = new CatListDisplayer(
      array_merge(self::$atts,
        ['link_titles' => 'false', 'title_tag' => 'h2']
      )
    );

    $this->assertSame(
      '<h2>Lcp test post</h2>',
      $displayer->get_post_title(self::$test_post)
    );
    $this->assertSame(
      '<h2>Lcp test post</h2>',
      self::$displayer_default->get_post_title(
        self::$test_post, 'h2', null, false
      )
    );
  }

  public function test_no_link_title_with_class() {

    $displayer = new CatListDisplayer(
      array_merge(self::$atts,
        ['link_titles' => 'false', 'title_class' => 'my-class']
      )
    );

    $this->assertSame(
      '<span class="my-class">Lcp test post</span>',
      $displayer->get_post_title(self::$test_post)
    );
    $this->assertSame(
      '<span class="my-class">Lcp test post</span>',
      self::$displayer_default->get_post_title(
        self::$test_post, null, 'my-class', false
      )
    );
  }

  public function test_no_link_title_with_tag_and_class() {

    $displayer = new CatListDisplayer(
      array_merge(self::$atts,
        ['link_titles' => 'false', 'title_tag' => 'h2', 'title_class' => 'my-class']
      )
    );

    $this->assertSame(
      '<h2 class="my-class">Lcp test post</h2>',
      $displayer->get_post_title(self::$test_post)
    );
    $this->assertSame(
      '<h2 class="my-class">Lcp test post</h2>',
      self::$displayer_default->get_post_title(
        self::$test_post, 'h2', 'my-class', false
      )
    );
  }

  public function test_title_limit() {

    $displayer = new CatListDisplayer(
      array_merge(self::$atts,
        ['title_limit' => '4']
      )
    );
    $expected = '<a href="' . get_permalink(self::$test_post) .'"'
                . '>Lcp &hellip;</a>';

    $this->assertSame(
      $expected,
      $displayer->get_post_title(self::$test_post)
    );
  }

  public function test_post_suffix() {

    $displayer = new CatListDisplayer(
      array_merge(self::$atts,
        ['post_suffix' => 'suffix']
      )
    );
    $expected = '<a href="' . get_permalink(self::$test_post) .'"'
                . '>Lcp test post</a> suffix';

    $this->assertSame(
      $expected,
      $displayer->get_post_title(self::$test_post)
    );
  }

  public function test_no_post_titles() {

    $displayer = new CatListDisplayer(
      array_merge(self::$atts,
        ['no_post_titles' => 'yes']
      )
    );

    $this->assertNull($displayer->get_post_title(self::$test_post));
  }

  public function test_shortcode_params_take_precedence() {

    $displayer = new CatListDisplayer(
      array_merge(self::$atts,
        [
          'link_titles' => 'false',
          'title_class' => 'my-class',
          'title_tag'   => 'h2',
        ]
      )
    );

    $this->assertSame(
      '<h2 class="my-class">Lcp test post</h2>',
      $displayer->get_post_title(self::$test_post, 'h1', 'test', true)
    );

    $displayer = new CatListDisplayer(
      array_merge(self::$atts, ['title_tag' => 'h2'])
    );
    $expected = '<h2>'
                . '<a href="' . get_permalink(self::$test_post) .'"'
                . '>Lcp test post</a></h2>';

    $this->assertSame(
      $expected,
      $displayer->get_post_title(self::$test_post, 'h1')
    );

    $displayer = new CatListDisplayer(
      array_merge(self::$atts, ['title_class' => 'my-class'])
    );
    $expected = '<a href="' . get_permalink(self::$test_post) .'"'
                . ' class="my-class">Lcp test post</a>';

    $this->assertSame(
      $expected,
      $displayer->get_post_title(self::$test_post, null, 'test-class')
    );

    $displayer = new CatListDisplayer(
      array_merge(self::$atts,
        ['title_class' => 'my-class', 'title_tag' => 'h2'])
    );
    $expected = '<h2 class="my-class">'
                . '<a href="' . get_permalink(self::$test_post) .'">'
                . 'Lcp test post</a></h2>';

    $this->assertSame(
      $expected,
      $displayer->get_post_title(self::$test_post, 'h1', 'test-class')
    );
  }

  public function test_private() {

    $private_post = self::factory()->post->create_and_get(array(
      'post_status' => 'private',
      'post_title'  => 'Private post',
    ));
    $expected = '<a href="' . get_permalink($private_post) .'">'
                . 'Private post</a>'
                . '<span class="lcp_private"> private</span>';

    $this->assertSame(
      $expected,
      self::$displayer_default->get_post_title($private_post)
    );
  }

  public function test_target() {

    $displayer = new CatListDisplayer(
      array_merge(self::$atts, ['link_target' => '_blank'])
    );
    $expected = '<a href="' . get_permalink(self::$test_post) .'"'
                . ' target="_blank">'
                . 'Lcp test post</a>';

    $this->assertSame(
      $expected,
      $displayer->get_post_title(self::$test_post)
    );
  }

  public function test_link_current_no() {

    $this->go_to('/?p=' . self::$test_post->ID);
    $this->assertQueryTrue('is_singular', 'is_single');

    $displayer = new CatListDisplayer(
      array_merge(self::$atts, ['link_current' => 'no'])
    );

    $this->assertSame(
      'Lcp test post',
      $displayer->get_post_title(self::$test_post)
    );
  }
}
