<?php

// https://developer.wordpress.org/reference/classes/wp_meta_query/
class Tests_LcpParameters_CreateMetaQueryArgs extends WP_UnitTestCase {
  private static $lcp_parameters;

  public static function wpSetUpBeforeClass($factory) {
    self::$lcp_parameters = LcpParameters::get_instance();
  }

  public function test_simple_customfield_params() {
    $this->assertSame(
      ['meta_query' => [
        'relation'      => 'AND',
        'select_clause' => [
          'key'   => 'test1',
          'value' => '10'
        ]
      ]
    ],
      self::$lcp_parameters->create_meta_query_args([],
        [
          'customfield_name'    => 'test1',
          'customfield_value'   => '10',
          'customfield_compare' => '',
          'customfield_orderby' => '',
        ]
      )
    );
  }

  public function test_customfield_orderby() {
    $this->assertSame(
      [
        'meta_query' => [
          'relation' => 'AND',
          'orderby_clause' => [
            'key'     => 'test1',
            'compare' => 'EXISTS'
          ]
        ],
        'orderby' => 'orderby_clause'
      ],
      self::$lcp_parameters->create_meta_query_args([],
        [
          'customfield_name'    => '',
          'customfield_value'   => '',
          'customfield_compare' => '',
          'customfield_orderby' => 'test1',
        ]
      )
    );
  }

  public function test_customfield_compare() {
    $this->assertSame(
      [
        'meta_query' => [
          'relation' => 'AND',
          [
            'relation' => 'AND',
            [
              'type'    => 'NUMERIC',
              'key'     => 'test1',
              'compare' => '<',
              'value'   => '10',
            ],
            [
              'type'    => 'CHAR',
              'key'     => 'test2',
              'compare' => 'NOT EXISTS',
            ],
          ]
        ]
      ],
      self::$lcp_parameters->create_meta_query_args([],
        [
          'customfield_name'    => '',
          'customfield_value'   => '',
          'customfield_compare' => 'test1,lessthan,10,numeric;test2,not_exists',
          'customfield_orderby' => '',
        ]
      )
    );
  }

  public function test_all_together() {
    $this->assertSame(
      [
        'meta_query' => [
          'relation' => 'AND',
          'select_clause' => [
            'key'   => 'test3',
            'value' => '20'
          ],
          'orderby_clause' => [
            'key'     => 'test4',
            'compare' => 'EXISTS'
          ],
          [
            'relation' => 'AND',
            [
              'type'    => 'NUMERIC',
              'key'     => 'test1',
              'compare' => '<',
              'value'   => '10',
            ],
            [
              'type'    => 'CHAR',
              'key'     => 'test2',
              'compare' => 'NOT EXISTS',
            ],
          ],
        ],
        'orderby' => 'orderby_clause'
      ],
      self::$lcp_parameters->create_meta_query_args([],
        [
          'customfield_name'    => 'test3',
          'customfield_value'   => '20',
          'customfield_compare' => 'test1,lessthan,10,numeric;test2,not_exists',
          'customfield_orderby' => 'test4',
        ]
      )
    );
  }
}
