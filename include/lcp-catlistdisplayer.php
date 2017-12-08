<?php
/**
 * This is an auxiliary class to help display the info
 * on your CatList instance.
 * @author fernando@picandocodigo.net
 */
require_once 'lcp-catlist.php';
require_once 'lcp-wrapper.php';
require_once 'lcp-templater.php';

class CatListDisplayer {
  public $catlist;
  private $wrapper;
  private $templater;
  private $params = array();
  private $lcp_output;
  private $lcp_query;

  public function __construct($atts) {
    $this->params = $atts;
    $this->catlist = new CatList($atts);
    $this->wrapper = LcpWrapper::get_instance();
    global $post;
    $this->parent = $post;
    $this->templater = new LcpTemplater($atts['template']);
  }

  public function display(){
    if ('no' === $this->params['main_query']) {
      $this->lcp_query = $this->catlist->get_posts();
      $this->create_output();
      wp_reset_postdata();
    } else {
      $this->catlist->save_wp_query();
      $this->lcp_query = $this->catlist->get_posts();
      $this->create_output();
      $this->catlist->restore_wp_query();
      wp_reset_query();
    }

    return $this->lcp_output;
  }

  private function create_output() {
    require $this->templater->get_template();
  }

  /**
   * Auxiliary functions for templates
   */

  /* Use outside The Loop */

  private function open_outer_tag($tag='ul', $css_class='lcp_catlist') {
    $this->templater->update_outer_tag($tag);
    return $this->catlist->get_outer_tag($this->templater->outer_tag, $css_class);
  }

  private function close_outer_tag() {
    return '</' . $this->templater->outer_tag . '>';
  }

  public function get_morelink($tag = null, $css_class = null){
    return $info = $this->content_getter('morelink', null, $tag, $css_class);
  }

  public function get_category_link($tag = 'strong', $css_class = null){
    return $this->content_getter('catlink', null, $tag, $css_class);
  }

  public function get_conditional_title($tag = 'h3', $css_class = null){
    return $this->content_getter('conditional_title', null, $tag, $css_class);
  }

  public function get_pagination(){
    return $this->catlist->get_pagination();
  }

  public function get_category_count(){
    return $this->catlist->get_category_count();
  }

  public function get_category_description($tag = null, $css_class = null) {
    return $this->content_getter('category_description', null, $tag, $css_class);
  }

  public function get_no_posts_text() {
    return $this->catlist->get_no_posts_text();
  }

  /* Use within The Loop */

  private function open_inner_tag($single, $tag, $css_class='') {
    $this->templater->update_inner_tag( $tag );
    return $this->catlist->get_inner_tag(
      $single,
      $this->parent,
      $this->templater->inner_tag,
      $css_class
    );
  }

  private function close_inner_tag() {
    return '</' . $this->templater->inner_tag . '>';
  }

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

  public function get_posts_tags($single, $tag = null, $css_class = null){
    return $this->content_getter('posts_tags', $single, $tag, $css_class);
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

  private function get_display_id($single) {
    return $this->catlist->get_display_id($single);
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
    case 'category_description':
      $info = $this->catlist->get_category_description();
      break;
    case 'date':
      $info = $this->catlist->get_date_to_show($post);
      if ( !empty($this->params['link_dates']) && ( 'yes' === $this->params['link_dates'] || 'true' === $this->params['link_dates'] ) ):
      $info = $this->get_post_link($post, $info);
      endif;
      ($info) ? ($info = ' ' . $info) : null;
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
      break;
    case 'posts_tags':
      $info = $this->catlist->get_posts_tags($post);
      break;
    }
    return $this->wrapper->wrap($info, $tag, $css_class);
  }

  private function get_post_link($single, $text, $class = null){

    $props = ['href' => get_permalink($single->ID)];

    if (!empty($class)) {
      $props['class'] = $class;
    }

    if (!empty($this->params['link_target'])) {
      $props['target'] = $this->params['link_target'];
    }

    $output = $this->wrapper->to_html('a', $props, $text);

    if ($single->post_status == 'private') {
        $output .= $this->wrapper->wrap(' private', 'span', 'lcp_private');
    }

    return $output;
  }

  // Link is a parameter here in case you want to use it on a template
  // and not show the links for all the shortcodes using this template:
  public function get_post_title($single, $tag = null, $css_class = null,
    $link = true, $link_current = true) {

    // Don't do anything if no_post_titles is specified.
    if ('yes' === $this->params['no_post_titles']) {
      return;
    }

    // Shortcode parameters take precedence.
    $tag = $this->params['title_tag'] ?: $tag;
    $css_class = $this->params['title_class'] ?: $css_class;
    $suffix = $this->params['post_suffix'] ? ' ' . $this->params['post_suffix'] : '';

    if ('no' === $this->params['link_current']) {
      $link_current = false;
    }

    if (in_array($this->params['link_titles'], ['false', 'no'], true)
      || ((is_object( $this->parent) && is_object($single) && $this->parent->ID === $single->ID)
        && !$link_current)) {
      $link = false;
    }

    $post_title = apply_filters('the_title', $single->post_title, $single->ID);
    $post_title = $this->lcp_title_limit($post_title);

    if (!$link) {
      $post_title .= $suffix;
      $output = $this->wrapper->wrap($post_title, $tag, $css_class);
    } else if (empty($tag)) {
      $output = $this->get_post_link($single, $post_title, $css_class) . $suffix;
    } else if (!empty($tag)) {
      $output = $this->get_post_link($single, $post_title) . $suffix;
      $output = $this->wrapper->wrap($output, $tag, $css_class);
    }

    return $output;
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

  /**
   * Checks if a protected post should be included in the LCP output.
   *
   *
   * @param  object $post A post to be checked.
   * @return bool         Whether a post should be included in the LCP output.
   */
  private function check_show_protected($post) {
    return ! post_password_required($post) ||
      post_password_required($post) && 'yes' === $this->params['show_protected'];
  }
}
