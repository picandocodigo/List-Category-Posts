<?php
/**
 * This class handles HTML customizations
 * defined by the user, both in shortcode parameters (e.g. comments_tag)
 * and in template method calls.
 */

require_once 'lcp-utils.php';

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
        return $this->to_html($tag, [], $info);
      endif;
      $css_class = LcpUtils::sanitize_html_classes($css_class);
      return $this->to_html($tag, ['class' => $css_class], $info);
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

  /**
   * Builds HTML elements.
   *
   * Wraps $content in HTML tag specified in $tag. If $close is false
   * the closing tag is omitted (useful when $content is null and we only
   * want to open the tag). $properties is an associative array with HTML
   * properties. Tag can be passed unescaped, all other parameters
   * are expected to be safe.
   *
   * @param  string  $tag        HTML tag (ex. 'p', 'div', 'li').
   * @param  array   $properties Optional. HTML element properties in
   *                             `'property_name' => value` format.
   * @param  string  $content    Optional. HTML element text content.
   * @param  boolean $close      Optional. If false, closing tag is omitted.
   * @return string              Generated HTML.
   */
  public function to_html($tag, $properties=[], $content=null, $close=true) {
    $props_str = '';
    foreach ($properties as $property => $value) {
      $props_str .= ' ' . $property . '="' . $value . '"';
    }

    $html = '<' . esc_attr($tag) . $props_str . '>' . $content;
    if ($close) $html .= '</' . esc_attr($tag) . '>';

    return $html;
  }
}
