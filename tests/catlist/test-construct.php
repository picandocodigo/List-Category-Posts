<?php

class Tests_CatList_Construct extends WP_UnitTestCase {

  public function test_instantiation() {
    $catlist = new CatList(['instance' => 1]);

    $this->assertTrue($catlist instanceof CatList);
  }
}