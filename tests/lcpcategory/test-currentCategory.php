<?php

class Tests_LcpCategory_CurrentCategory extends WP_UnitTestCase {

  protected static $test_post;
  protected static $test_page;
  protected static $test_cat;

  public static function wpSetUpBeforeClass($factory) {
    // Create some random categories
    $factory->term->create_many(5, array(
      'taxonomy' => 'category'
    ));
    // Add some posts to each category
    foreach(get_categories(array('hide_empty' => 0)) as $category) {
      $factory->post->create_many(10, array(
        'post_category' => array($category->cat_ID)
      ));
    }

    // Create test category, test post and test page (no categories for page)
    self::$test_cat = $factory->term->create(array(
      'taxonomy' => 'category',
      'name' => 'Lcp test cat'
    ));

    self::$test_post = $factory->post->create(array(
      'post_title' => 'Lcp test post',
      'post_category' => array(self::$test_cat)
    ));

    self::$test_page = $factory->post->create(array(
      'post_title' => 'Lcp test page',
      'post_type' => 'page'
    ));
  }

  public function test_category_archive() {
    $lcpcategory = LcpCategory::get_instance();

    // Check if every category is detected properly
    $categories = get_categories();
    foreach($categories as $category) {
      $this->go_to('/?cat=' . $category->cat_ID);
      $this->assertQueryTrue('is_category', 'is_archive');

      $this->assertSame($category->cat_ID, $lcpcategory->current_category());
    }
  }

  public function test_single_post_page() {
    $lcpcategory = LcpCategory::get_instance();

    $this->go_to('/?p=' . self::$test_post);
    $this->assertQueryTrue('is_singular', 'is_single');
    $this->assertSame(self::$test_cat, $lcpcategory->current_category());
    $this->assertSame('Lcp test cat', get_category($lcpcategory->current_category())->cat_name);
  }

  public function test_single_page_with_no_categories() {
    $lcpcategory = LcpCategory::get_instance();

    $this->go_to('/?page_id=' . self::$test_page);
    $this->assertQueryTrue('is_singular', 'is_page');
    $this->assertSame([0], $lcpcategory->current_category());
  }

  public function test_home_page() {
    $lcpcategory = LcpCategory::get_instance();

    $this->go_to('/');
    $this->assertQueryTrue('is_home', 'is_front_page');
    $this->assertSame([0], $lcpcategory->current_category());
  }

  public function test_date_archive() {
    $lcpcategory = LcpCategory::get_instance();

    $this->go_to(get_month_link('',''));
    $this->assertQueryTrue('is_archive', 'is_date', 'is_month');
    $this->assertSame([0], $lcpcategory->current_category());
  }

  public function test_author_archive() {
    $lcpcategory = LcpCategory::get_instance();

    $this->go_to(get_author_posts_url(1));
    $this->assertQueryTrue('is_archive', 'is_author');
    $this->assertSame([0], $lcpcategory->current_category());
  }
}