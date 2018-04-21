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
}