<?php
/**
 * Class to build pagination
 * @author fernando@picandocodigo.net
 */

class LcpPaginator {
  private $catlist;
  private $prev_page_num;
  private $next_page_num;

  // Singleton implementation
  private static $instance = null;
  public static function get_instance(){
    if( !isset( self::$instance ) ){
      self::$instance = new self;
    }
    return self::$instance;
  }

  # Define if pagination should be displayed based on 'pagination' param and option.
  # Check if the pagination option is set to true, and the param
  # is not set to 'no' (since shortcode parameters should
  # override general options).
  # Receives params['pagination'] from CatList

  public function get_pagination($params){
    if (LcpUtils::lcp_show_pagination($params['pagination'])){
      $lcp_paginator = '';
      $pages_count = ceil (
        $params['posts_count'] /
        # Avoid dividing by 0 (pointed out by @rhj4)
        max( array( 1, $params['numberposts'] ) )
      );
      $pag_output = '';
      $this->prev_page_num = null;
      $this->next_page_num = null;
      $lcp_elipsis = "<span class='lcp_elipsis'>...</span>";
      if ( $pages_count > 1 ) {
        /* Dynamic pagination inspired by
         * https://gist.github.com/shlomohass/9869e138a4fba0e7dc4c
         */
        // Print first page.
        $lcp_paginator .=  $this->lcp_page_link(
          1,
          $params['page'],
          $params['instance'],
          $params['bookmarks']
        );
        // Padding around current page. How many pages will be printed
        // before and after current page.
        $pad = intval( $params['padding'] );
        // Print opening ellipsis if needed
        $params['page'] - $pad > 2 && $lcp_paginator .= $lcp_elipsis;
        // Loop over pages excluding first and last page.
        for( $i = 2; $i < $pages_count; $i++ ) {
          if ( $i >= $params['page'] - $pad && $i <= $params['page'] + $pad ) {
            $lcp_paginator .=  $this->lcp_page_link(
              $i,
              $params['page'],
              $params['instance'],
              $params['bookmarks']
            );
          }
        }
        // Print closing ellipsis if needed
        $params['page'] + $pad < $pages_count - 1 && $lcp_paginator .= $lcp_elipsis;
        // Print last page.
        $lcp_paginator .=  $this->lcp_page_link(
          $pages_count,
          $params['page'],
          $params['instance'],
          $params['bookmarks']
        );

        $pag_output .= "<ul class='lcp_paginator'>";

        // Add "Previous" link
        if ($params['page'] > 1){
          $this->prev_page_num = intval(intval($params['page']) - 1);
          $pag_output .= $this->lcp_page_link(
            $this->prev_page_num,
            $params['page'],
            $params['instance'],
            $params['bookmarks'], $params['previous']
          );
        }

        $pag_output .= $lcp_paginator;

        // Add "Next" link
        if ($params['page'] < $pages_count){
          $this->next_page_num = intval($params['page'] + 1);
          $pag_output .= $this->lcp_page_link(
            $this->next_page_num,
            $params['page'],
            $params['instance'],
            $params['bookmarks'], $params['next']
          );
        }

        $pag_output .= "</ul>";
      }
      $pag_output = apply_filters( 'lcp_pagination_html', $pag_output, $params, $pages_count );
      return $pag_output;
    }
  }


  // `char` is the string from pagination_prev/pagination_next
    private function lcp_page_link($page, $current_page, $lcp_instance, $bookmark, $char = null){
    $link = '';

    if ($page == $current_page){
      $link = "<li class='lcp_currentpage'>" . esc_html($current_page) . '</li>';
    } else {
      $server_vars = add_magic_quotes($_SERVER);
      $request_uri = $server_vars['REQUEST_URI'];
      $query = $server_vars['QUERY_STRING'];
      $amp = ( strpos( $request_uri, "?") ) ? "&" : "";
      $pattern = "/[&|?]?lcp_page" . preg_quote($lcp_instance) . "=([0-9]+)/";
      $query = preg_replace($pattern, '', $query);

      $url = strtok($request_uri,'?');
      $protocol = "http";
      $port = $server_vars['SERVER_PORT'];
      if ( (!empty($server_vars['HTTPS']) && $server_vars['HTTPS'] !== 'off') || $port == 443){
        $protocol = "https";
      }
      $http_host = $server_vars['HTTP_HOST'];
      $page_link = "$protocol://$http_host$url?$query" .
                   $amp . "lcp_page" . $lcp_instance . "=". $page;

      // Append a bookmark if not disabled by 'pagination_bookmarks=no'
      if ($bookmark !== "no") $page_link .= "#lcp_instance_" . $lcp_instance;

      // WA: Replace '?&' by '?' to avoid potential redirection problems later on
      $page_link = str_replace('?&', '?', $page_link );

      $link .=  "<li><a href='" . esc_url($page_link) . "' title='" . esc_attr($page) . "'";
      if ($page === $this->prev_page_num) {
        $link .= " class='lcp_prevlink'";
      } elseif ($page === $this->next_page_num) {
        $link .= " class='lcp_nextlink'";
      }
      $link .= ">";
      ($char != null) ? ($link .= esc_html($char)) : ($link .= esc_html($page));

      $link .= "</a></li>";
    }
    return $link;
  }
}
