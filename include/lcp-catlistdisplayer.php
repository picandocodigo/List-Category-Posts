<?php
/**
 * This is an auxiliary class to help display the info
 * on your CatList instance.
 * @author fernando@picandocodigo.net
 */
require_once 'lcp-catlist.php';
require_once 'lcp-wrapper.php';

class CatListDisplayer {
  public $catlist;
  private $wrapper;
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
    $this->wrapper = LcpWrapper::get_instance();
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
    $this->lcp_output .= $this->get_category_link();

    $this->lcp_output .= $this->get_category_description();

    $this->lcp_output .= $this->get_conditional_title();

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
    $lcp_display_output .= $this->get_comments($single);

    // Date
    $lcp_display_output .= $this->get_date($single);

    // Date Modified
    $lcp_display_output .= $this->get_modified_date($single);

    // Author
    $lcp_display_output .= $this->get_author($single);

    // Display ID
    if (!empty($this->params['display_id']) && $this->params['display_id'] == 'yes'){
        $lcp_display_output .= $single->ID;
    }

    // Custom field display
    $lcp_display_output .= $this->get_custom_fields($single);

    $lcp_display_output .= $this->get_thumbnail($single);

    $lcp_display_output .= $this->get_content($single);

    $lcp_display_output .= $this->get_excerpt($single);

    $lcp_display_output .= $this->get_posts_morelink($single);

    $lcp_display_output .= '</' . $tag . '>';
    return $lcp_display_output;
  }

  /**
   * Auxiliary functions for templates
   */

  /* Use outside The Loop */

  private function get_morelink($tag = null, $css_class = null){
    return $info = $this->content_getter('morelink', null, $tag, $css_class);
  }

  private function get_category_link($tag = 'strong', $css_class = null){
    return $this->content_getter('catlink', null, $tag, $css_class);
  }

  private function get_conditional_title($tag = 'h3', $css_class = null){
    return $this->content_getter('conditional_title', null, $tag, $css_class);
  }

  private function get_pagination(){
    return $this->catlist->get_pagination();
  }

  public function get_category_count(){
    return $this->catlist->get_category_count();
  }

  public function get_category_description(){
    return $this->catlist->get_category_description();
  }

  /* Use within The Loop */

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

  private function get_modified_date($single, $tag = null, $css_class = null){
    return $info = $this->content_getter('date_modified', $single, $tag, $css_class);
  }

  private function get_custom_fields($single, $tag='div', $css_class='lcp-customfield'){
    return $this->content_getter('customfield', $single, $tag, $css_class);
  }

  private function get_date($single, $tag=null, $css_class=null) {
    return $this->content_getter('date', $single, $tag, $css_class);
  }

  private function get_thumbnail($single, $tag=null, $css_class=null) {
    return $this->content_getter('thumbnail', $single, $tag, $css_class);
  }

  private function get_posts_morelink($single, $css_class=null) {
    return $this->content_getter('posts_morelink', $single, null, $css_class);
  }

  /*
  * These used to be separate functions, now starting to get the code
  * in the same function for less repetition.
  */
  private function content_getter($type, $post, $tag = null, $css_class = null) {
    // Shortcode parameters take precedence over function arguments
    // for tags and classes. 'posts_morelink_tag' param doesn't exist
    if ($type !== 'posts_morelink') $tag = $this->params[$type . '_tag'] ?: $tag;
    $css_class = $this->params[$type . '_class'] ?: $css_class;

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
      break;
    case 'date_modified':
      $info = $this->catlist->get_modified_date_to_show($post);
      break;
    case 'morelink':
      $info = $this->catlist->get_morelink();
      break;
    case 'catlink':
      $info = $this->catlist->get_category_link();
      break;
    case 'conditional_title':
      $info = $this->catlist->get_conditional_title();
      break;
    case 'customfield':
      $info = $this->catlist->get_custom_fields($this->params['customfield_display'], $post->ID);
      break;
    case 'date':
      $info = $this->catlist->get_date_to_show($post);
      if ( !empty($this->params['link_dates']) && ( 'yes' === $this->params['link_dates'] || 'true' === $this->params['link_dates'] ) ):
      $info = $this->get_post_link($post, $info);
      endif;
      $info = ' ' . $info;
      break;
    case 'thumbnail':
      $info = $this->catlist->get_thumbnail($post, $css_class);
      // Default wrapper behavior not supported here,
      // class is only used inside the <img> element.
      $css_class = null;
      break;
    case 'posts_morelink':
      $info = $this->catlist->get_posts_morelink($post, $css_class);
      // Default wrapper behavior not supported here,
      // class is only used inside the <a> element.
      $css_class = null;
    }
    return $this->wrapper->wrap($info, $tag, $css_class);
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
    if($single->post_status == 'private'):
        $info .= '<span class="lcp_private"> private</span>';
    endif;

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
      $info = $this->wrapper->wrap($info, $tag, $css_class);
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
}