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
  private function show_pagination($pagination){
    return (!empty($pagination) && (
            $pagination == 'yes' ||
            $pagination == 'true')
           )
           ||
           (get_option('lcp_pagination') === 'true' &&
            ($pagination !== 'false') &&
            ($pagination !== 'no')
           );
  }

  public function get_pagination($params){
    if ($this->show_pagination($params['pagination'])){
      $lcp_paginator = '';
      $pages_count = ceil (
          $params['posts_count'] /
          # Avoid dividing by 0 (pointed out by @rhj4)
          max( array( 1, $params['numberposts'] ) )
      );
      $pag_output = '';
      $this->prev_page_num = null;
      $this->next_page_num = null;
      if ($pages_count > 1){
          for($i = 1; $i <= $pages_count; $i++){
              $lcp_paginator .=  $this->lcp_page_link($i, $params['page'], $params['instance']);
          }

          $pag_output .= "<ul class='lcp_paginator'>";

          // Add "Previous" link
          if ($params['page'] > 1){
            $this->prev_page_num = intval(intval($params['page']) - 1);
            $pag_output .= $this->lcp_page_link($this->prev_page_num , $params['page'], $params['instance'], $params['previous'] );
          }

          $pag_output .= $lcp_paginator;

          // Add "Next" link
          if ($params['page'] < $pages_count){
            $this->next_page_num = intval($params['page'] + 1);
            $pag_output .= $this->lcp_page_link($this->next_page_num, $params['page'], $params['instance'], $params['next']);
          }

          $pag_output .= "</ul>";
      }
      return $pag_output;
    }
  }


  // `char` is the string from pagination_prev/pagination_next
  private function lcp_page_link($page, $current_page, $lcp_instance, $char = null){
    $link = '';

    if ($page == $current_page){
      $link = "<li class='lcp_currentpage'>$current_page</li>";
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
                   $amp . "lcp_page" . $lcp_instance . "=". $page .
                   "#lcp_instance_" . $lcp_instance;
      $link .=  "<li><a href='$page_link' title='$page'";
      if ($page === $this->prev_page_num) {
          $link .= " class='lcp_prevlink'";
      } elseif ($page === $this->next_page_num) {
          $link .= " class='lcp_nextlink'";
      }
      $link .= ">";
      ($char != null) ? ($link .= $char) : ($link .= $page);

      $link .= "</a></li>";
    }
    // WA: Replace '?&' by '?' to avoid potential redirection problems later on
    $link = str_replace('?&', '?', $link );
    return $link;
  }
}