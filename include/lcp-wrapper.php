<?php
/**
 * This class handles HTML customizations
 * defined by the user, both in shortcode parameters (e.g. comments_tag)
 * and in template method calls.
 */
class LcpWrapper {

  // Singleton implementation
  private static $instance = null;
  public static function get_instance(){
    if( !isset( self::$instance ) ){
      self::$instance = new self;
    }
    return self::$instance;
  }

  /**
   * Several checks going on here:
   * - Tag provided, no class - wrap content with tag
   * - Tag and class provided - wrap content with tag and class
   * - Class provided, no tag - wrap content with span and class
   * @param string $info
   * @param string $tag
   * @param string $css_class
   * @return string
   */
  private function assign_style($info, $tag = null, $css_class = null){
    if (!empty($info)):
      if (empty($tag) && !empty($css_class)):
        $tag = "span";
      elseif (empty($tag)):
        return $info;
      elseif (!empty($tag) && empty($css_class)) :
        return '<' . $tag . '>' . $info . '</' . $tag . '>';
      endif;
      $css_class = sanitize_html_class($css_class);
      return '<' . $tag . ' class="' . $css_class . '">' . $info . '</' . $tag . '>';
    endif;
  }

  /**
   * Assign style to the info delivered by CatList. Tag is an HTML tag
   * which is passed and will sorround the info. Css_class is the css
   * class we want to assign to this tag. If an array is passed to $info
   * each element will be wrapped separately and added to the returned string.
   * @param string|array $info
   * @param string $tag
   * @param string $css_class
   * @return string
   */
  public function wrap($info, $tag=null, $css_class=null) {

    $wrapped = '';

    if (is_array($info)) {
      foreach ($info as $i) {
        $wrapped .= $this->assign_style($i, $tag, $css_class);
      }
    } else {
      $wrapped = $this->assign_style($info, $tag, $css_class);
    }
    return $wrapped;
  }
}