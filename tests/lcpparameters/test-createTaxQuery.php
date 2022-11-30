<?php

// https://developer.wordpress.org/reference/classes/wp_tax_query/
class Tests_LcpParameters_CreateTaxQuery extends WP_UnitTestCase {
  private static $lcp_parameters, $test_term, $test_term_2, $test_post,
    $test_post_2, $test_page;
  
  public static function wpSetUpBeforeClass($factory) {
    self::$lcp_parameters = LcpParameters::get_instance();

    // Create some random terms
    register_taxonomy('mouse', 'post');
    $factory->term->create_many(5, array(
      'taxonomy' => 'mouse'
    ));
    // Create test terms, test posts.
    self::$test_term = $factory->term->create(array(
      'taxonomy' => 'mouse',
      'name' => 'mickey'
    ));

    self::$test_term_2 = $factory->term->create(array(
      'taxonomy' => 'mouse',
      'name' => 'minnie'
    ));

    self::$test_post = $factory->post->create(array(
      'post_title' => 'Lcp test post',
    ));
    wp_set_post_terms(self::$test_post, 'mickey', 'mouse');

    // Post with 2 terms
    self::$test_post_2 = $factory->post->create(array(
      'post_title' => 'Lcp test post',
    ));
    wp_set_post_terms(self::$test_post_2, 'mickey', 'minnie');
  }

  public function test_simple_taxonomies() {
    $this->assertEquals(
      [
        'tax_query' => [[
          'taxonomy' => 'mouse',
          'field'    => 'slug',
          'terms'    => ['mickey'],
          'operator' => 'IN',
        ]]
      ],
      self::$lcp_parameters->create_tax_query([],
        [
          'taxonomy'     => 'mouse',
          'terms'        => 'mickey',
          'currentterms' => '',
        ]
      )
    );

    $this->assertEquals(
      [
        'tax_query' => [[
          'taxonomy' => 'mouse',
          'field'    => 'slug',
          'terms'    => ['mickey', 'minnie'],
          'operator' => 'IN',
        ]]
      ],
      self::$lcp_parameters->create_tax_query([],
        [
          'taxonomy'     => 'mouse',
          'terms'        => 'mickey,minnie',
          'currentterms' => '',
        ]
      )
    );

    $this->assertEquals(
      [
        'tax_query' => [[
          'taxonomy' => 'mouse',
          'field'    => 'slug',
          'terms'    => ['mickey', 'minnie'],
          'operator' => 'AND',
        ]]
      ],
      self::$lcp_parameters->create_tax_query([],
        [
          'taxonomy'     => 'mouse',
          'terms'        => 'mickey+minnie',
          'currentterms' => '',
        ]
      )
    );
  }

  public function test_multiple_taxonomies() {
    $this->assertEquals(
      [
        'tax_query' => [
          'relation' => 'OR',
          [
            'taxonomy' => 'tax1',
            'field'    => 'slug',
            'terms'    => ['term1_1', 'term1_2'],
          ],
          [
            'taxonomy' => 'tax2',
            'field'    => 'slug',
            'terms'    => ['term2_1', 'term2_2', 'term2_3'],
          ]
        ]
      ],
      self::$lcp_parameters->create_tax_query([],
        [
          'taxonomies_or' => 'tax1:{term1_1,term1_2};tax2:{term2_1,term2_2,term2_3}',
          'currentterms'  => '',
        ]
      )
    );

    $this->assertEquals(
      [
        'tax_query' => [
          'relation' => 'AND',
          [
            'taxonomy' => 'tax1',
            'field'    => 'slug',
            'terms'    => ['term1_1', 'term1_2'],
          ],
          [
            'taxonomy' => 'tax2',
            'field'    => 'slug',
            'terms'    => ['term2_1', 'term2_2', 'term2_3'],
          ]
        ]
      ],
      self::$lcp_parameters->create_tax_query([],
        [
          'taxonomies_and'=> 'tax1:{term1_1,term1_2};tax2:{term2_1,term2_2,term2_3}',
          'currentterms'  => '',
        ]
      )
    );
  }

  public function test_current_terms() {
    $this->go_to('/?p=' . self::$test_post);
    $this->assertQueryTrue('is_singular', 'is_single');
    $this->assertEquals(
      [
        'tax_query' => [[
          'taxonomy' => 'mouse',
          'field'    => 'term_id',
          'terms'    => [ self::$test_term ],
          'operator' => 'IN',
        ]]
      ],
      self::$lcp_parameters->create_tax_query([],
        [
          'taxonomy'     => 'mouse',
          'currentterms' => 'yes',
        ]
      )
    );
  }
}