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
}