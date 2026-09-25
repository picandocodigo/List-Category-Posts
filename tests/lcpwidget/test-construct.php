<?php

class Tests_ListCategoryPostsWidget_Construct extends WP_UnitTestCase {

  public function test_instantiation() {
    $widget = new ListCategoryPostsWidget();

    $this->assertTrue($widget instanceof ListCategoryPostsWidget);
  }

  public function test_created_properties() {
    $widget = new ListCategoryPostsWidget();

    $this->assertSame('listcategorypostswidget', $widget->id_base);
    $this->assertSame(__('List Category Posts','list-category-posts'), $widget->name);
    $this->assertSame(__('List posts from a specified category','list-category-posts'),
                      $widget->widget_options['description']);
  }

  public function test_widget_handles_missing_optional_checkbox_settings() {
    $category_id = self::factory()->category->create();
    self::factory()->post->create(array('post_category' => array($category_id)));

    $widget = new ListCategoryPostsWidget();
    $args = array(
      'before_widget' => '',
      'after_widget' => '',
      'before_title' => '',
      'after_title' => '',
    );
    $instance = array(
      'title' => '',
      'limit' => 5,
      'orderby' => 'date',
      'order' => 'desc',
      'exclude' => '',
      'excludeposts' => '',
      'offset' => 0,
      'categoryid' => $category_id,
      'show_date' => '',
      'show_excerpt' => '',
      'excerpt_size' => 55,
      'show_author' => '',
      'show_catlink' => '',
      'thumbnail' => '',
      'thumbnail_size' => 'thumbnail',
      'morelink' => '',
      'tags_as_class' => 'no',
      'template' => 'default',
    );

    ob_start();
    $widget->widget($args, $instance);
    $output = ob_get_clean();

    $this->assertNotEmpty($output);
  }
}
