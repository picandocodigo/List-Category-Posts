<?php

class Tests_CatList_GetPosts extends WP_UnitTestCase {

  public function test_main_query_behavior_by_default() {
    self::factory()->post->create_many(2);
    $catlist = new CatList(ListCategoryPosts::default_params());
    $query = $catlist->get_posts();

    global $wp_query;
    $this->assertSame($query, $wp_query);
  }

  public function test_secondary_query() {
    self::factory()->post->create_many(2);
    $catlist = new CatList(array_merge(
      ListCategoryPosts::default_params(),
      ['main_query' => 'no']
    ));
    $query = $catlist->get_posts();

    global $wp_query;
    $this->assertNotSame($query, $wp_query);
  }
}
