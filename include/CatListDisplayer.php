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
    // More link
    if (!empty($this->params['catlink_tag'])):
      if (!empty($this->params['catlink_class'])):
        $this->lcp_output .= $this->get_category_link(
                                   $this->params['catlink_tag'],
                                   $this->params['catlink_class']);
      else:
        $this->lcp_output .= $this->get_category_link($this->params['catlink_tag']);
      endif;
    else:
      $this->lcp_output .= $this->get_category_link("strong");
    endif;

    $this->lcp_output .= '<' . $tag;

    //Give a class to wrapper tag
    if (isset($this->params['class'])):
      $this->lcp_output .= ' class="' . $this->params['class'] . '"';
    endif;

    //Give id to wrapper tag
    if (isset($this->params['instance'])){
      $this->lcp_output .= ' id=lcp_instance_' . $this->params['instance'];
    }

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

    // More link
    if (!empty($this->params['morelink_tag'])):
      if (!empty($this->params['morelink_class'])):
        $this->lcp_output .= $this->get_morelink(
                                   $this->params['morelink_tag'],
                                   $this->params['morelink_class']);
      else:
        $this->lcp_output .= $this->get_morelink($this->params['morelink_tag']);
      endif;
    else:
      $this->lcp_output .= $this->get_morelink();
    endif;

    if (!empty($this->params['pagination']) && $this->params['pagination'] == "yes"):
      $lcp_paginator = '';
      $pages_count = ceil (
                           $this->catlist->get_posts_count() / $this->catlist->get_number_posts()
                           );
      for($i = 1; $i <= $pages_count; $i++){
        $lcp_paginator .=  $this->lcp_page_link($i, true);
      }

      $this->lcp_output .= "<ul class='lcp_paginator'>";

      // Add "Previous" link
      if ($this->catlist->get_page() > 1){
        $this->lcp_output .= $this->lcp_page_link( intval($this->catlist->get_page()) - 1, "<<" );
      }

      $this->lcp_output .= $lcp_paginator;
      // Add "Next" link
      if ($this->catlist->get_page() < $pages_count){
        $this->lcp_output .= $this->lcp_page_link( intval($this->catlist->get_page()) + 1, ">>");
      }
      $this->lcp_output .= "</ul>";

    endif;
  }

  private function lcp_page_link($page, $char = nil){
    $current_page = $this->catlist->get_page();
    $link = '';

    if ($page == $current_page){
      $link = "<li>$current_page</li>";
    } else {
      $amp = ( strpos($_SERVER["REQUEST_URI"], "?") ) ? "&" : "";
      $pattern = "/[&|?]?lcp_page" . preg_quote($this->catlist->get_instance()) . "=([0-9]+)/";
      $query = preg_replace($pattern, '', $_SERVER['QUERY_STRING']);

      $url = strtok($_SERVER["REQUEST_URI"],'?');

      $page_link = "http://$_SERVER[HTTP_HOST]$url?$query" .
        $amp . "lcp_page" . $this->catlist->get_instance() . "=". $page .
        "#lcp_instance_" . $this->catlist->get_instance();
      $link .=  "<li><a href='$page_link' title='$page'>";
      ($char != nil) ? ($link .= $char) : ($link .= $page);
      $link .= "</a></li>";
    }
    return $link;
  }

  /**
   * This function should be overriden for template system.
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

    if (!empty($this->params['title_tag'])):
      if (!empty($this->params['title_class'])):
        $lcp_display_output .= $this->get_post_title($single,
                                         $this->params['title_tag'],
                                         $this->params['title_class']);
      else:
        $lcp_display_output .= $this->get_post_title($single, $this->params['title_tag']);
      endif;
    else:
      $lcp_display_output .= $this->get_post_title($single) . ' ';
    endif;

    // Comments count
    if (!empty($this->params['comments_tag'])):
      if (!empty($this->params['comments_class'])):
        $lcp_display_output .= $this->get_comments($single,
                                       $this->params['comments_tag'],
                                       $this->params['comments_class']);
      else:
        $lcp_display_output .= $this->get_comments($single, $this->params['comments_tag']);
      endif;
    else:
      $lcp_display_output .= $this->get_comments($single);
    endif;

    // Date
    if (!empty($this->params['date_tag']) || !empty($this->params['date_class'])):
      $lcp_display_output .= $this->get_date($single,
                                             $this->params['date_tag'],
                                             $this->params['date_class']);
    else:
      $lcp_display_output .= $this->get_date($single);
    endif;

    // Author
    if (!empty($this->params['author_tag'])):
      if (!empty($this->params['author_class'])):
        $lcp_display_output .= $this->get_author($single,
                                     $this->params['author_tag'],
                                     $this->params['author_class']);
      else:
        $lcp_display_output .= $this->get_author($single, $this->params['author_tag']);
      endif;
    else:
      $lcp_display_output .= $this->get_author($single);
    endif;


    if (!empty($this->params['customfield_display'])) :
      $lcp_display_output .=
        $this->get_custom_fields($this->params['customfield_display'],
                                 $single->ID);
    endif;

    $lcp_display_output .= $this->get_thumbnail($single);

    if (!empty($this->params['content_tag'])):
      if (!empty($this->params['content_class'])):
        $lcp_display_output .= $this->get_content($single,
                                     $this->params['content_tag'],
                                     $this->params['content_class']);
      else:
        $lcp_display_output .= $this->get_content($single, $this->params['content_tag']);
      endif;
    else:
      $lcp_display_output .= $this->get_content($single);
    endif;

    if (!empty($this->params['excerpt_tag'])):
      if (!empty($this->params['excerpt_class'])):
        $lcp_display_output .= $this->get_excerpt($single,
                                     $this->params['excerpt_tag'],
                                     $this->params['excerpt_class']);
      else:
        $lcp_display_output .= $this->get_excerpt($single, $this->params['excerpt_tag']);
      endif;
    else:
      $lcp_display_output .= $this->get_excerpt($single);
    endif;

    if (!empty($this->params['posts_morelink'])) :
      $href = 'href="'.get_permalink($single->ID) . '"';
      $class = "";
      if (!empty($this->params['posts_morelink_class'])) :
        $class = 'class="' . $this->params['posts_morelink_class'] . '" ';
      endif;
      $readmore = $this->params['posts_morelink'];
      $lcp_display_output .= ' <a ' . $href . ' ' . $class . ' >' . $readmore . '</a>';
    endif;

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
    if ( !empty($this->params['thumbnail_class']) ) :
      $lcp_thumb_class = $this->params['thumbnail_class'];
      $info = $this->catlist->get_thumbnail($single, $lcp_thumb_class);
    else:
      $info = $this->catlist->get_thumbnail($single);
    endif;

    return $this->assign_style($info, $tag);
  }

  private function get_post_title($single, $tag = null, $css_class = null){
    $info = '<a href="' . get_permalink($single->ID) .
      '" title="' . wptexturize($single->post_title) . '"';
    if (!empty($this->params['link_target'])):
      $info .= ' target="' . $this->params['link_target'] . '" ';
    endif;
    $info .= '>' . apply_filters('the_title', $single->post_title, $single->ID) . '</a>';

    if( !empty($this->params['post_suffix']) ):
      $info .= " " . $this->params['post_suffix'];
    endif;

    return $this->assign_style($info, $tag, $css_class);
  }

  private function get_category_link($tag = null, $css_class = null){
    $info = $this->catlist->get_category_link();
    return $this->assign_style($info, $tag, $css_class);
  }
  
  private function get_morelink($tag = null, $css_class = null){
    $info = $this->catlist->get_morelink();
    return $this->assign_style($info, $tag, $css_class);
  }

  private function get_category_count(){
    return $this->catlist->get_category_count();
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
      if (empty($tag) && !empty($css_class)):
        $tag = "span";
      elseif (empty($tag)):
        return $info;
      elseif (!empty($tag) && empty($css_class)) :
        return '<' . $tag . '>' . $info . '</' . $tag . '>';
      endif;
      return '<' . $tag . ' class="' . $css_class . '">' . $info . '</' . $tag . '>';
    endif;
  }
}