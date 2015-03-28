<?php
/**
 * This is an auxiliary class to help display the info
 * on your CatList instance.
 * @author fernando@picandocodigo.net
 */
require_once 'lcp-catlist.php';

class CatListDisplayer {
  private $catlist;
  private $params = array();
  private $lcp_output;
  public static function getTemplatePaths(){
    $template_path = TEMPLATEPATH . "/list-category-posts/";
    $stylesheet_path = STYLESHEETPATH . "/list-category-posts/";
    return array($template_path, $stylesheet_path);
  }

  public function __construct($atts) {
    $this->params = $atts;
    $this->catlist = new CatList($atts);
    $this->select_template();
  }

  public function display(){
    return $this->lcp_output;
  }

  private function select_template(){
    // Check if we got a template param:
    if (isset($this->params['template']) &&
      !empty($this->params['template'])){
      // The default values for ul, ol and div:
      if (preg_match('/^ul$|^div$|^ol$/i', $this->params['template'], $matches)){
        $this->build_output($matches[0]);
      } else {
        // Else try an actual template from the params
        $this->template();
      }
    } else {
      // Default:
      $this->build_output('ul');
    }
  }

  /**
   * Template code
   */
  private function template(){
    $tplFileName = null;
    $template_param = $this->params['template'];
    $templates = array();

    // Get templates paths and add the incoming parameter to search
    // for the php file:
    if($template_param){
      $paths = self::getTemplatePaths();
      foreach($paths as $path){
        $templates[] = $path . $template_param . '.php';
      }
    }

    // Check if we can read the template file:
    foreach ($templates as $file) :
      if ( is_file($file) && is_readable($file) ) :
        $tplFileName = $file;
      endif;
    endforeach;

    if($tplFileName){
      require($tplFileName);
    } else {
      $this->build_output('ul');
    }
  }

  public static function get_templates($param = null){
    $templates = array();
    $paths = self::getTemplatePaths();
    foreach ($paths as $templatePath){
      if (is_dir($templatePath) && scandir($templatePath)){
        foreach (scandir($templatePath) as $file){
          // Check that the files found are well formed
          if ( ($file[0] != '.') && (substr($file, -4) == '.php') &&
          is_file($templatePath.$file) && is_readable($templatePath.$file) ){
            $templateName = substr($file, 0, strlen($file)-4);
            // Add the template only if necessary
            if (!in_array($templateName, $templates)){
              $templates[] = $templateName;
            }
          }
        }
      }
    }
    return $templates;
  }

  private function build_output($tag){
    $this->category_title();

    $this->lcp_output .= '<' . $tag;

    // Follow the numner of posts in an ordered list with pagination
    if( $tag == 'ol' && $this->catlist->get_page() > 1 ){
      $start = $this->catlist->get_number_posts() * ($this->catlist->get_page() - 1) + 1;
      $this->lcp_output .= ' start="' .  $start . '" ';
    }
    //Give a class to wrapper tag
    if (isset($this->params['class'])):
      $this->lcp_output .= ' class="' . $this->params['class'] . '"';
    endif;

    //Give id to wrapper tag
    if (isset($this->params['instance'])){
      $this->lcp_output .= ' id="lcp_instance_' . $this->params['instance'] . '"';
    }

    $this->lcp_output .= '>';
    $inner_tag = ( ($tag == 'ul') || ($tag == 'ol') ) ? 'li' : 'p';

    //Posts loop
    foreach ($this->catlist->get_categories_posts() as $single) :
      if ( !post_password_required($single) ||
           ( post_password_required($single) && (
                                                 isset($this->params['show_protected']) &&
                                                 $this->params['show_protected'] == 'yes' ) )):
        $this->lcp_output .= $this->lcp_build_post($single, $inner_tag);
      endif;
    endforeach;

    if ( ($this->catlist->get_posts_count() == 0) &&
         ($this->params["no_posts_text"] != '') ) {
      $this->lcp_output .= $this->params["no_posts_text"];
    }


    //Close wrapper tag
    $this->lcp_output .= '</' . $tag . '>';

    // More link
    $this->lcp_output .= $this->get_morelink();


    $this->lcp_output .= $this->get_pagination();
  }

  public function get_pagination(){
    $pag_output = '';
    if (!empty($this->params['pagination']) && $this->params['pagination'] == "yes"):
      $lcp_paginator = '';
      $number_posts = $this->catlist->get_number_posts();
      $pages_count = ceil (
        $this->catlist->get_posts_count() /
        # Avoid dividing by 0 (pointed out by @rhj4)
        max( array( 1, $number_posts ) )
      );
      if ($pages_count > 1){
        for($i = 1; $i <= $pages_count; $i++){
          $lcp_paginator .=  $this->lcp_page_link($i);
        }

        $pag_output .= "<ul class='lcp_paginator'>";

        // Add "Previous" link
        if ($this->catlist->get_page() > 1){
          $pag_output .= $this->lcp_page_link( intval($this->catlist->get_page()) - 1, $this->params['pagination_prev'] );
        }

        $pag_output .= $lcp_paginator;

        // Add "Next" link
        if ($this->catlist->get_page() < $pages_count){
          $pag_output .= $this->lcp_page_link( intval($this->catlist->get_page()) + 1, $this->params['pagination_next']);
        }

        $pag_output .= "</ul>";
      }
    endif;
    return $pag_output;
  }

  private function lcp_page_link($page, $char = null){
    $current_page = $this->catlist->get_page();
    $link = '';

    if ($page == $current_page){
      $link = "<li>$current_page</li>";
    } else {
      $amp = ( strpos($_SERVER["REQUEST_URI"], "?") ) ? "&" : "";
      $pattern = "/[&|?]?lcp_page" . preg_quote($this->catlist->get_instance()) . "=([0-9]+)/";
      $query = preg_replace($pattern, '', $_SERVER['QUERY_STRING']);

      $url = strtok($_SERVER["REQUEST_URI"],'?');
      $protocol = "http";
      if ( (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
        $_SERVER['SERVER_PORT'] == 443){
        $protocol = "https";
      }

      $page_link = "$protocol://$_SERVER[HTTP_HOST]$url?$query" .
        $amp . "lcp_page" . $this->catlist->get_instance() . "=". $page .
        "#lcp_instance_" . $this->catlist->get_instance();
      $link .=  "<li><a href='$page_link' title='$page'>";

      ($char != null) ? ($link .= $char) : ($link .= $page);

      $link .= "</a></li>";
    }
    // WA: Replace '?&' by '?' to avoid potential redirection problems later on
    $link = str_replace('?&', '?', $link );
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


    if ( $this->params['no_post_titles'] != 'yes' ):
      $lcp_display_output .= $this->get_post_title($single);
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

    // Date Modified
    if (!empty($this->params['date_modified_tag']) || !empty($this->params['date_modified_class'])):
      $lcp_display_output .= $this->get_modified_date($single,
                                             $this->params['date_modified_tag'],
                                             $this->params['date_modified_class']);
    else:
      $lcp_display_output .= $this->get_modified_date($single);
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

    // Display ID
    if (!empty($this->params['display_id']) && $this->params['display_id'] == 'yes'){
        $lcp_display_output .= $single->ID;
    }

    // Custom field display
    $lcp_display_output .= $this->get_custom_fields($single);

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

    $lcp_display_output .= $this->get_posts_morelink($single);

    $lcp_display_output .= '</' . $tag . '>';
    return $lcp_display_output;
  }

  private function category_title(){
    // More link
    if (!empty($this->params['catlink_tag'])):
      if (!empty($this->params['catlink_class'])):
        $this->lcp_output .= $this->get_category_link(
          $this->params['catlink_tag'],
          $this->params['catlink_class']
        );
      else:
        $this->lcp_output .= $this->get_category_link($this->params['catlink_tag']);
      endif;
    else:
      $this->lcp_output .= $this->get_category_link("strong");
    endif;
  }

  /**
   * Auxiliary functions for templates
   */
  private function get_comments($single, $tag = null, $css_class = null){
    return $this->content_getter('comments', $single, $tag, $css_class);
  }


  private function get_author($single, $tag = null, $css_class = null){
    return $this->content_getter('author', $single, $tag, $css_class);
  }

  private function get_content($single, $tag = null, $css_class = null){
    return $this->content_getter('content', $single, $tag, $css_class);
  }

  private function get_excerpt($single, $tag = null, $css_class = null){
    return $this->content_getter('excerpt', $single, $tag, $css_class);
  }

/*
 * These used to be separate functions, now starting to get the code
 * in the same function for less repetition.
 */
  private function content_getter($type, $post, $tag = null, $css_class = null) {
    $info = '';
    switch( $type ){
    case 'comments':
      $info = $this->catlist->get_comments_count($post);
      break;
    case 'author':
      $info = $this->catlist->get_author_to_show($post);
      break;
    case 'content':
      $info = $this->catlist->get_content($post);
      break;
    case 'excerpt':
      $info = $this->catlist->get_excerpt($post);
      $info = preg_replace('/\[.*\]/', '', $info);
    }
    return $this->assign_style($info, $tag, $css_class);
  }

  private function get_custom_fields($single){
    if(!empty($this->params['customfield_display'])){
      $info = $this->catlist->get_custom_fields($this->params['customfield_display'], $single->ID);
      if(empty($this->params['customfield_tag']) || $this->params['customfield_tag'] == null)
        $tag = 'div';
      if(empty($this->params['customfield_class']) || $this->params['customfield_class'] == null)
        $css_class = 'lcp_customfield';
      return $this->assign_style($info, $tag, $css_class);
    }
  }

  private function get_date($single, $tag = null, $css_class = null){
    $info = " " . $this->catlist->get_date_to_show($single);
    return $this->assign_style($info, $tag, $css_class);
  }

  private function get_modified_date($single, $tag = null, $css_class = null){
    $info = " " . $this->catlist->get_modified_date_to_show($single);
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

  // Link is a parameter here in case you want to use it on a template
  // and not show the links for all the shortcodes using this template:
  private function get_post_title($single, $tag = null, $css_class = null, $link = true){
    if ( !$link || !empty($this->params['link_titles']) && $this->params['link_titles'] == "false" ) {
      return $single->post_title;
    }

    $info = '<a href="' . get_permalink($single->ID);

    $lcp_post_title = apply_filters('the_title', $single->post_title, $single->ID);

    if ( !empty($this->params['title_limit']) && $this->params['title_limit'] != "0" ):
      $lcp_post_title = substr($lcp_post_title, 0, intval($this->params['title_limit']));
      if( strlen($lcp_post_title) >= intval($this->params['title_limit']) ):
        $lcp_post_title .= "&hellip;";
      endif;
    endif;

    $info.=  '" title="' . wptexturize($single->post_title) . '"';

    if (!empty($this->params['link_target'])):
      $info .= ' target="' . $this->params['link_target'] . '" ';
    endif;

    if ( !empty($this->params['title_class'] ) &&
         empty($this->params['title_tag']) ):
      $info .= ' class="' . $this->params['title_class'] . '"';
    endif;

    $info .= '>' . $lcp_post_title . '</a>';

    if( !empty($this->params['post_suffix']) ):
      $info .= " " . $this->params['post_suffix'];
    endif;

    if (!empty($this->params['title_tag'])){
      $pre = "<" . $this->params['title_tag'];
      if (!empty($this->params['title_class'])){
        $pre .= ' class="' . $this->params['title_class'] . '"';
      }
      $pre .= '>';
      $post = "</" . $this->params['title_tag'] . ">";
      $info = $pre . $info . $post;
    }

    if( $tag !== null || $css_class !== null){
      $info = $this->assign_style($info, $tag, $css_class);
    }

    return $info;
  }

  private function get_posts_morelink($single){
    if(!empty($this->params['posts_morelink'])){
      $href = 'href="' . get_permalink($single->ID) . '"';
      $class = "";
      if ( !empty($this->params['posts_morelink_class']) ):
        $class = 'class="' . $this->params['posts_morelink_class'] . '" ';
      endif;
      $readmore = $this->params['posts_morelink'];
      return ' <a ' . $href . ' ' . $class . ' >' . $readmore . '</a>';
    }
  }

  private function get_category_link($tag = null, $css_class = null){
    $info = $this->catlist->get_category_link();
    return $this->assign_style($info, $tag, $css_class);
  }

  private function get_morelink(){
    $info = $this->catlist->get_morelink();
    if ( !empty($this->params['morelink_tag'])){
      if( !empty($this->params['morelink_class']) ){
        return "<" . $this->params['morelink_tag'] . " class='" .
          $this->params['morelink_class'] . "'>" . $info .
          "</" . $this->params["morelink_tag"] . ">";
      } else {
        return "<" . $this->params['morelink_tag'] . ">" .
          $info . "</" . $this->params["morelink_tag"] . ">";
      }
    } else{
      if ( !empty($this->params['morelink_class']) ){
        return str_replace("<a", "<a class='" . $this->params['morelink_class'] . "' ", $info);
      }
    }
    return $info;
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
      $css_class = sanitize_html_class($css_class);
      return '<' . $tag . ' class="' . $css_class . '">' . $info . '</' . $tag . '>';
    endif;
  }
}
