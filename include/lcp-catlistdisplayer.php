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
    global $post;
    $this->parent = $post;
  }

  public function display(){
    $this->catlist->save_wp_query();
    $this->catlist->get_posts();
    $this->select_template();
    $this->catlist->restore_wp_query();
    wp_reset_query();
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

    $this->get_category_description();

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

    $this->lcp_output .= $this->get_conditional_title();

    //Posts loop
    global $post;
    while ( have_posts() ) : the_post();
      if ( !post_password_required($post) ||
           ( post_password_required($post) && (
                                                 isset($this->params['show_protected']) &&
                                                 $this->params['show_protected'] == 'yes' ) )):
        $this->lcp_output .= $this->lcp_build_post($post, $inner_tag);
      endif;
    endwhile;

    if ( ($this->catlist->get_posts_count() == 0) &&
         ($this->params["no_posts_text"] != '') ) {
      $this->lcp_output .= $this->params["no_posts_text"];
    }

    //Close wrapper tag
    $this->lcp_output .= '</' . $tag . '>';

    // More link
    $this->lcp_output .= $this->get_morelink();

    $this->lcp_output .= $this->catlist->get_pagination();
  }

  public function get_pagination(){
    $pag_output = '';
    $lcp_pag_present = !empty($this->params['pagination']);
    if ($lcp_pag_present && $this->params['pagination'] == "yes" ||
        # Check if the pagination option is set to true, and the param
        # is not set to 'no' (since shortcode parameters should
        # override general options.
        (get_option('lcp_pagination') === 'true' && ($lcp_pag_present && $this->params['pagination'] !== 'false'))):
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
      $link = "<li class='lcp_currentpage'>$current_page</li>";
    } else {
      $server_vars = add_magic_quotes($_SERVER);
      $request_uri = $server_vars['REQUEST_URI'];
      $query = $server_vars['QUERY_STRING'];
      $amp = ( strpos( $request_uri, "?") ) ? "&" : "";
      $pattern = "/[&|?]?lcp_page" . preg_quote($this->catlist->get_instance()) . "=([0-9]+)/";
      $query = preg_replace($pattern, '', $query);

      $url = strtok($request_uri,'?');
      $protocol = "http";
      $port = $server_vars['SERVER_PORT'];
      if ( (!empty($server_vars['HTTPS']) && $server_vars['HTTPS'] !== 'off') || $port == 443){
        $protocol = "https";
      }
      $http_host = $server_vars['HTTP_HOST'];
      $page_link = "$protocol://$http_host$url?$query" .
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
    $class ='';
    $tag_css = '';
    if ( is_object($this->parent) && is_object($single) && $this->parent->ID == $single->ID ){
      $class = 'current';
    }

    if ( array_key_exists('tags_as_class', $this->params) && $this->params['tags_as_class'] == 'yes' ) {
      $post_tags = wp_get_post_Tags($single->ID);
      if ( !empty($post_tags) ){
        foreach ($post_tags as $post_tag) {
          $class .= " $post_tag->slug ";
        }
      }
    }
    if ( !empty($class) ){
      $tag_css = 'class="' . $class . '"';
    }
    $lcp_display_output = '<'. $tag . ' ' . $tag_css . '>';

    if ( empty($this->params['no_post_titles']) || !empty($this->params['no_post_titles']) && $this->params['no_post_titles'] !== 'yes' ) {
      $lcp_display_output .= $this->get_post_title($single);
    }

    // Comments count
    $lcp_display_output .= $this->get_stuff_with_tags_and_classes('comments', $single);

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
    $lcp_display_output .= $this->get_stuff_with_tags_and_classes('author', $single);

    // Display ID
    if (!empty($this->params['display_id']) && $this->params['display_id'] == 'yes'){
        $lcp_display_output .= $single->ID;
    }

    // Custom field display
    $lcp_display_output .= $this->get_custom_fields($single);

    $lcp_display_output .= $this->get_thumbnail($single);

    $lcp_display_output .= $this->get_stuff_with_tags_and_classes('content', $single);

    $lcp_display_output .= $this->get_stuff_with_tags_and_classes('excerpt', $single);

    $lcp_display_output .= $this->get_posts_morelink($single);

    $lcp_display_output .= '</' . $tag . '>';
    return $lcp_display_output;
  }

  /**
   * Several checks going on here:
   * - Tag provided, no class - wrap content with tag
   * - Tag and class provided - wrap content with tag and class
   * - Class provided, no tag - wrap content with span and class
  */
  private function get_stuff_with_tags_and_classes($entity, $single){
    $result = '';
    $stuffFunction = 'get_' . $entity;
    if (!empty($this->params[$entity . '_class'])){
      if (empty($this->params[$entity . '_tag'])){
        $result = $this->$stuffFunction($single, 'span', $this->params[$entity . '_class']);
      } else {
        $result = $this->$stuffFunction($single, $this->params[$entity . '_tag'], $this->params[$entity . '_class']);
      }
    } else {
      if (!empty($this->params[$entity . '_tag'])){
        $result = $this->$stuffFunction($single, $this->params[$entity . '_tag']);
      } else {
        $result = $this->$stuffFunction($single);
      }
    }
    return $result;
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

  public function get_category_description(){
    if(!empty($this->params['category_description']) && $this->params['category_description'] == 'yes'){
      $this->lcp_output .= $this->catlist->get_category_description();
    }
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

  private function get_conditional_title(){
    if(!empty($this->params['conditional_title_tag']))
      $tag = $this->params['conditional_title_tag'];
    else
      $tag = 'h3';
    if(!empty($this->params['conditional_title_class']))
      $class = $this->params['conditional_title_class'];
    else
      $class = '';

    return $this->assign_style($this->catlist->get_conditional_title(), $tag, $class);
  }

  private function get_custom_fields($single){
    if(!empty($this->params['customfield_display'])){
      $info = $this->catlist->get_custom_fields($this->params['customfield_display'], $single->ID);
      if(empty($this->params['customfield_tag']) || $this->params['customfield_tag'] == null){
        $tag = 'div';
      } else {
        $tag = $this->params['customfield_tag'];
      }

      if(empty($this->params['customfield_class']) || $this->params['customfield_class'] == null){
        $css_class = 'lcp_customfield';
      } else {
        $css_class = $this->params['customfield_class'];
      }

      $final_info = '';
      if(!is_array($info)){
        $final_info = $this->assign_style($info, $tag, $css_class);
      }else{
        if($this->params['customfield_display_separately'] != 'no'){
          foreach($info as $i)
            $final_info .= $this->assign_style($i, $tag, $css_class);
        }else{
          $one_info = implode($this->params['customfield_display_glue'], $info);
          $final_info = $this->assign_style($one_info, $tag, $css_class);
        }
      }
      return $final_info;
    }
  }

  private function get_date($single, $tag = null, $css_class = null){
    $info = $this->catlist->get_date_to_show($single);

    if ( !empty($this->params['link_dates']) && ( 'yes' === $this->params['link_dates'] || 'true' === $this->params['link_dates'] ) ):
      $info = $this->get_post_link($single, $info);
    endif;

    $info = ' ' . $info;
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

  private function get_post_link($single, $text, $class = null){
    $info = '<a href="' . get_permalink($single->ID) . '" title="' . wptexturize($single->post_title) . '"';

    if ( !empty($this->params['link_target']) ):
      $info .= ' target="' . $this->params['link_target'] . '"';
    endif;

    if ( !empty($class ) ):
      $info .= ' class="' . $class . '"';
    endif;

    $info .= '>' . $text . '</a>';

    return $info;
  }

  // Link is a parameter here in case you want to use it on a template
  // and not show the links for all the shortcodes using this template:
  private function get_post_title($single, $tag = null, $css_class = null, $link = true){
    $lcp_post_title = apply_filters('the_title', $single->post_title, $single->ID);

    $lcp_post_title = $this->lcp_title_limit( $lcp_post_title );

    if ( !empty($this->params['title_tag']) ) {
      $pre = "<" . $this->params['title_tag'];
      if (!empty($this->params['title_class'])){
        $pre .= ' class="' . $this->params['title_class'] . '"';
      }
      $pre .= '>';
      $post = "</" . $this->params['title_tag'] . ">";
    }else{
      $pre = $post = '';
    }

    if ( !$link || ( !empty($this->params['link_titles'] ) &&
          ( $this->params['link_titles'] === "false" || $this->params['link_titles'] === "no" ) ) ) {
      return $pre . $lcp_post_title . $post;
    }

    $info = $this->get_post_link($single, $lcp_post_title, (!empty($this->params['title_class']) && empty($this->params['title_tag'])) ? $this->params['title_class'] : null);

    if( !empty($this->params['post_suffix']) ):
      $info .= " " . $this->params['post_suffix'];
    endif;

    $info = $pre . $info . $post;

    if( $tag !== null || $css_class !== null){
      $info = $this->assign_style($info, $tag, $css_class);
    }

    return $info;
  }

  // Transform the title into the sub string if `title_limit` is present
  private function lcp_title_limit( $lcp_post_title ){
    if ( !empty($this->params['title_limit']) && $this->params['title_limit'] !== "0" ){
      $title_limit = intval($this->params['title_limit']);
      if( function_exists('mb_strlen') && function_exists('mb_substr') && mb_strlen($lcp_post_title) > $title_limit ){
        $lcp_post_title = mb_substr($lcp_post_title, 0, $title_limit) . "&hellip;";
      } else {
        if( strlen($lcp_post_title) > $title_limit ){
          $lcp_post_title = substr($lcp_post_title, 0, $title_limit) . "&hellip;";
        }
      }
    }
    return $lcp_post_title;
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

  public function get_category_count(){
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
