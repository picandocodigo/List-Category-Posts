<?php
/**
 * This is an auxiliary class to help display the info
 * on your CatList.php instance.
 * @author fernando@picandocodigo.net
 */
require_once 'CatList.php';

class CatListDisplayer {
  private $catlist;
  private $params = array();
  private $lcp_output;

  public function __construct($atts) {
    $this->params = $atts;
    $this->catlist = new CatList($atts);
    $this->template();
  }

  public function display(){
    return $this->lcp_output;
  }

  /**
   * Template code
   */
  private function template(){
    $tplFileName = null;
    $possibleTemplates = array(
      // File locations lower in list override others
      TEMPLATEPATH.'/list-category-posts/'.$this->params['template'].'.php',
      STYLESHEETPATH.'/list-category-posts/'.$this->params['template'].'.php'
    );

    foreach ($possibleTemplates as $key => $file) :
      if ( is_readable($file) ) :
        $tplFileName = $file;
      endif;
    endforeach;

    if ( !empty($tplFileName) && is_readable($tplFileName) ) :
      require($tplFileName);
    else:
      switch($this->params['template']):
      case "default":
        $this->build_output('ul');
        break;
      case "div":
        $this->build_output('div');
        break;
      default:
        $this->build_output('ul');
        break;
      endswitch;
    endif;
  }

  private function build_output($tag){
    $this->lcp_output .= $this->get_category_link('strong');
    $this->lcp_output .= '<' . $tag;

    //Give a class to wrapper tag
    if (isset($this->params['class'])):
      $this->lcp_output .= ' class="' . $this->params['class'] . '"';
    endif;
    $this->lcp_output .= '>';
    $inner_tag = ($tag == 'ul') ? 'li' : 'p';

    //Posts loop
    foreach ($this->catlist->get_categories_posts() as $single) :
      if ( !post_password_required($single) ) :
        $this->lcp_output .= $this->lcp_build_post($single, $inner_tag);
      endif;
    endforeach;

    //Close wrapper tag
    $this->lcp_output .= '</' . $tag . '>';

    if (!empty($this->params['morelink'])) :
      $href = 'href="' . get_category_link($this->catlist->get_category_id()) . '"';
      $class = "";
      if (!empty($this->params['morelink_class'])) :
        $class = 'class="' . $this->params['morelink_class'] . '" ';
      endif;
      $readmore = $this->params['morelink'];
      $this->lcp_output .= '<a ' . $href . ' ' . $class . ' >' . $readmore . '</a>';
    endif;
  }

  /**
   *  This function should be overriden for template system.
   * @param post $single
   * @param HTML tag to display $tag
   * @return string
   */
  private function lcp_build_post($single, $tag){
    global $post;
    $class ='';
    if ( $post->ID == $single->ID ):
      $class = " class = current ";
    endif;
    $lcp_display_output = '<'. $tag . $class . '>';
    $lcp_display_output .=
      $this->get_post_title($single, $this->params['title_tag'],
                            $this->params['title_class']);
    $lcp_display_output .=
      $this->get_comments($single, $this->params['comments_tag'],
                          $this->params['comments_class']) . ' ';

    $lcp_display_output .=
      $this->get_date($single, $this->params['date_tag'],
                      $this->params['date_class']) . ' ';

    $lcp_display_output .=
      $this->get_author($single, $this->params['author_tag'],
                        $this->params['author_class']) . ' ';

    if (isset($this->params['customfield_display'])) :
      $lcp_display_output .=
        $this->get_custom_fields($this->params['customfield_display'],
                                 $single->ID);
    endif;

    $lcp_display_output .= $this->get_thumbnail($single);

    $lcp_display_output .= $this->get_content($single, $this->params['content_tag'], $this->params['content_class']);

    $lcp_display_output .= $this->get_excerpt($single, $this->params['excerpt_tag'], $this->params['excerpt_class']);;

    $lcp_display_output .= '</' . $tag . '>';

    return $lcp_display_output;
  }

  /**
   * Auxiliary functions for templates
   */
  private function get_author($single, $tag = null, $css_class = null){
    $info = $this->catlist->get_author_to_show($single);
    return $this->assign_style($info, $tag, $css_class);
  }

  private function get_comments($single, $tag = null, $css_class = null){
    $info = $this->catlist->get_comments_count($single);
    return $this->assign_style($info, $tag, $css_class);
  }

  private function get_content($single, $tag = null, $css_class = null){
    $info = $this->catlist->get_content($single);
    return $this->assign_style($info, $tag, $css_class);
  }

  private function get_custom_fields($custom_key, $post_id, $tag = null, $css_class = null){
    $info = $this->catlist->get_custom_fields($custom_key, $post_id);
    return $this->assign_style($info, $tag, $css_class);
  }

  private function get_date($single, $tag = null, $css_class = null){
    $info = $this->catlist->get_date_to_show($single);
    return $this->assign_style($info, $tag, $css_class);
  }

  private function get_excerpt($single, $tag = null, $css_class = null){
    $info = $this->catlist->get_excerpt($single);
    $info = preg_replace('/\[.*\]/', '', $info);
    return $this->assign_style($info, $tag, $css_class);
  }

  private function get_thumbnail($single, $tag = null){
    if ( isset($this->params['thumbnail_class']) &&
         $this->params['thumbnail_class'] != '' ) :
      $lcp_thumb_class = $this->params['thumbnail_class'];
      $info = $this->catlist->get_thumbnail($single, $lcp_thumb_class);
    else:
      $info = $this->catlist->get_thumbnail($single);
    endif;

    return $this->assign_style($info, $tag);
  }

  private function get_post_title($single, $tag = null, $css_class = null){
    $info = '<a href="' . get_permalink($single->ID) .
      '" title="'. $single->post_title . '">' .
      apply_filters('the_title', $single->post_title, $single->ID) . '</a>';
    return $this->assign_style($info, $tag, $css_class);
  }

  private function get_category_link($tag = null, $css_class = null){
    $info = $this->catlist->get_category_link();
    return $this->assign_style($info, $tag, $css_class);
  }

  /**
   * Assign style to the info delivered by CatList. Tag is an HTML tag
   * which is passed and will sorround the info. Css_class is the css
   * class we want to assign to this tag.
   * @param string $info
   * @param string $tag
   * @param string $css_class
   * @return string
   */
  private function assign_style($info, $tag = null, $css_class = null){
    if (!empty($info)):
      if (empty($tag)):
        return $info;
      elseif (!empty($tag) && empty($css_class)) :
        return '<' . $tag . '>' . $info . '</' . $tag . '>';
      endif;
      return '<' . $tag . ' class="' . $css_class . '">' . $info . '</' . $tag . '>';
    endif;
  }
}