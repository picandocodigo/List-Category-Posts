<?php
class LcpUtils{
  private $params;

  public function __construct($params){
    $this->params = $params;
  }
  /**
   * Check for empty parameters (being empty strings or zero).
   */
  public function lcp_not_empty($param){
    return (
      isset($this->params[$param]) &&
      !empty($this->params[$param]) &&
      $this->params[$param] !== '0' &&
      $this->params[$param] !== ''
    );
  }

  public static function lcp_orders(){
    return array("date" => __("Date", "list-category-posts"),
                 "modified" => __("Modified Date", "list-category-posts"),
                 "title" => __("Post title", "list-category-posts"),
                 "author" => __("Author", "list-category-posts"),
                 "ID" => __("ID", "list-category-posts"),
                 "rand" => __("Random", "list-category-posts"));
  }

  public static function lcp_show_pagination($pagination){
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
}
