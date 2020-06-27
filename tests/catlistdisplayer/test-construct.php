<?php

class Tests_CatListDisplayer_Construct extends WP_UnitTestCase {


  public function test_instantiation() {
    $displayer = new CatListDisplayer(['instance' => 1, 'template' => '']);

    $this->assertTrue($displayer instanceof CatListDisplayer);
  }

  public function test_created_properties() {
    $displayer = new CatListDisplayer(['instance' => 1, 'template' => '']);

    $this->assertTrue($displayer->catlist instanceof CatList);
    $this->assertTrue(property_exists($displayer, 'parent'));
  }
}