<?php
  /**
   * The CatList object gets the info for the CatListDisplayer to show.
   * Each time you use the shortcode, you get an instance of this class.
   * @author fernando@picandocodigo.net
   */
class CatList{
  private $params = array();
  private $lcp_category_id = 0;
  private $exclude;
  private $page = 1;
  private $posts_count = 0;
  private $instance = 0;

  /**
   * Constructor gets the shortcode attributes as parameter
   * @param array $atts
   */
  public function __construct($atts) {
      load_plugin_textdomain(
          'list-category-posts',
          false,
          dirname( plugin_basename( __FILE__ ) ) . '/languages/'
      );
    $this->params = $atts;

    if ($this->lcp_not_empty('instance')){
      $this->instance = $atts['instance'];
    }
    //Get the category posts:
    $this->get_lcp_category();
    $this->set_lcp_parameters();
  }

  /**
   * Order the parameters and query the DB for posts
   */
  private function set_lcp_parameters(){
    $args = $this->lcp_categories();
    $args = array_merge($args, array(
      'numberposts' => $this->params['numberposts'],
      'orderby' => $this->params['orderby'],
      'order' => $this->params['order'],
      'offset' => $this->params['offset']
    ));

    // Check posts to exclude
    $args = $this->lcp_check_excludes($args);

    // Check type, status, parent params
    $args = $this->lcp_types_and_statuses($args);

    if($this->lcp_not_empty('year')):
      $args['year'] = $this->params['year'];
    endif;

    if($this->lcp_not_empty('monthnum')):
      $args['monthnum'] = $this->params['monthnum'];
    endif;

    if($this->lcp_not_empty('search')):
      $args['s'] = $this->params['search'];
    endif;

    if($this->lcp_not_empty('author_posts')):
      $args['author_name'] = $this->params['author_posts'];
    endif;

    /*
     * Custom fields 'customfield_name' & 'customfield_value'
     * should both be defined
     */
    if( $this->lcp_not_empty('customfield_value') ):
      $args['meta_key'] = $this->params['customfield_name'];
      $args['meta_value'] = $this->params['customfield_value'];
    endif;

    //Get private posts
    if(is_user_logged_in()):
      if ( !empty($args['post_status']) ):
        $args['post_status'] = array_merge($args['post_status'], array('private'));
      else:
        $args['post_status'] = array('private', 'publish');
      endif;
    endif;

    if ( $this->lcp_not_empty('exclude_tags') ):
      $excluded_tags = explode(",", $this->params['exclude_tags']);
      $tag_ids = array();
      foreach ( $excluded_tags as $excluded):
        $tag_ids[] = get_term_by('slug', $excluded, 'post_tag')->term_id;
      endforeach;
      $args['tag__not_in'] = $tag_ids;
    endif;

    // Current tags
    if ( $this->lcp_not_empty('currenttags') && $this->params['currenttags'] == "yes" ):
      $tags = $this->lcp_get_current_tags();
      if ( !empty($tags) ):
        $args['tag__in'] = $tags;
      endif;
    endif;

    // Added custom taxonomy support
    if ( $this->lcp_not_empty('taxonomy') && $this->lcp_not_empty('tags') ):
      $args['tax_query'] = array(array(
                               'taxonomy' => $this->params['taxonomy'],
                               'field' => 'slug',
                               'terms' => explode(",",$this->params['tags'])
                                 ));
    elseif ( !empty($this->params['tags']) ):
      $args['tag'] = $this->params['tags'];
    endif;

    if ( !empty($this->params['exclude'])):
      $args['category__not_in'] = array($this->params['exclude']);
    endif;

    if ( $this->lcp_not_empty('customfield_orderby') ):
      $args['orderby'] = 'meta_value';
      $args['meta_key'] = $this->params['customfield_orderby'];
    endif;

    if ( $this->lcp_not_empty('pagination')):
      if( preg_match('/lcp_page' . preg_quote($this->instance) .
                     '=([0-9]+)/i', $_SERVER['QUERY_STRING'], $match) ):
        $this->page = $match[1];
        $offset = ($this->page - 1) * $this->params['numberposts'];
        $args = array_merge($args, array('offset' => $offset));
      endif;
    endif;

    // Posts that start with a given letter:
    if ( $this->lcp_not_empty('starting_with') ){
        add_filter('posts_where' , array( $this, 'starting_with') );
    }

    // for WP_Query compatibility
    // http://core.trac.wordpress.org/browser/tags/3.7.1/src/wp-includes/post.php#L1686
    $args['posts_per_page'] = $args['numberposts'];

    $query = new WP_Query;
    $this->lcp_categories_posts = $query->query($args);
    $this->posts_count = $query->found_posts;
    remove_all_filters('posts_orderby');
    remove_all_filters('posts_where');
  }

  public function starting_with($where){
      // Explode a string of letters separated with commas and 
      // build the $where query with OR statements to retrieve 
      // all posts that start with those letters
 
      $letters = explode(',', $this->params['starting_with']);
      $where .= ' AND wp_posts.post_title ' .
        'COLLATE UTF8_GENERAL_CI LIKE \'' . $letters[0] . "%'";
      for ($i=1; $i <sizeof($letters); $i++) { 
                $where .= 'OR wp_posts.post_title ' .
        'COLLATE UTF8_GENERAL_CI LIKE \'' . $letters[$i] . "%'";
      }
      
      return $where;
  }

  /* Should I return posts or show that the tag/category or whatever
    posts combination that I called has no posts? By default I've
    always returned the latest posts because that's what the query
    does when the params are "wrong". But could make for a better user
    experience if I returned an empty list in certain cases.
    private function lcp_should_return_posts() */

  /** HELPER FUNCTIONS **/

  /**
   * Check if there's one or more categories.
   * Used in the beginning when setting up the parameters.
   */
  private function lcp_categories(){
    if (is_array($this->lcp_category_id)):
      return array('category__and' => $this->lcp_category_id);
    else:
      return array('cat'=> $this->lcp_category_id);
    endif;
  }

  // Check posts to exclude
  private function lcp_check_excludes($args){
    if( $this->lcp_not_empty('excludeposts') ){
      $exclude = array(
        'post__not_in' => explode(",", $this->params['excludeposts'])
      );
      if (strpos($this->params['excludeposts'], 'this') > -1){
        $exclude = array_merge(
          $exclude,
          array('post__not_in' => array($this->lcp_get_current_post_id() ) )
        );
      }
      $args = array_merge($args, $exclude);
    }
    return $args;
  }

  private function lcp_types_and_statuses($args){
    // Post type, status, parent params:
    if($this->lcp_not_empty('post_type')):
      $args['post_type'] = $this->params['post_type'];
    endif;

    if($this->lcp_not_empty('post_status')):
      $args['post_status'] = array(
        $this->params['post_status']
      );
    endif;

    if($this->lcp_not_empty('post_parent')):
      $args['post_parent'] = $this->params['post_parent'];
    endif;
    return $args;
  }

  /**
   * Check for empty parameters (being empty strings or zero).
   */
  private function lcp_not_empty($param){
      return (
          isset($this->params[$param]) &&
          !empty($this->params[$param]) &&
          $this->params[$param] !== '0' &&
          $this->params[$param] !== ''
      );
  }

  private function lcp_get_current_post_id(){
    global $post;
    return $post->ID;
  }

  private function get_lcp_category(){
    if ( $this->lcp_not_empty('categorypage') &&
         $this->params['categorypage'] == 'yes' ||
         $this->params['id'] == -1):
      $this->lcp_category_id = $this->lcp_get_current_category();
    elseif ( $this->lcp_not_empty('name') ):
      if (preg_match('/\+/', $this->params['name'])):
        $categories = array();
        $cat_array = explode("+", $this->params['name']);

        foreach ($cat_array as $category) :
          $id = $this->get_category_id_by_name($category);
          $categories[] = $id;
        endforeach;

        $this->lcp_category_id = $categories;

      elseif (preg_match('/,/', $this->params['name'])):
        $categories = '';
        $cat_array = explode(",", $this->params['name']);

        foreach ($cat_array as $category) :
          $id = $this->get_category_id_by_name($category);
          $categories .= $id . ",";
        endforeach;

        $this->lcp_category_id = $categories;

      else:
        $this->lcp_category_id = $this->get_category_id_by_name($this->params['name']);
      endif;
    elseif ( isset($this->params['id']) && $this->params['id'] != '0' ):
      if (preg_match('/\+/', $this->params['id'])):
        if ( preg_match('/(-[0-9]+)+/', $this->params['id'], $matches) ):
          $this->exclude = implode(',', explode("-", ltrim($matches[0], '-') ));
        endif;
        $this->lcp_category_id = explode("+", $this->params['id']);
      else:
        $this->lcp_category_id = $this->params['id'];
      endif;
    endif;
  }

  public function lcp_get_current_category(){
    $category = get_category( get_query_var( 'category' ) );
    if(isset($category->errors) && $category->errors["invalid_term"][0] == __("Empty Term") ):
      global $post;
      $categories = get_the_category($post->ID);
      return $categories[0]->cat_ID;
    endif;
    return $category->cat_ID;
  }

  public function lcp_get_current_tags(){
    $tags = get_the_tags();
    $tag_ids = array();
    if( !empty($tags) ){
      foreach ($tags as $tag_id => $tag) {
        array_push($tag_ids, $tag_id);
      }
    }
    return $tag_ids;
  }

  /**
   * Get the category id from its name
   * by Eric Celeste / http://eric.clst.org
   */
  private function get_category_id_by_name($cat_name){
    //TODO: Support multiple names (this used to work, but not anymore)
    //We check if the name gets the category id, if not, we check the slug.
    $term = get_term_by('slug', $cat_name, 'category');
    if (!$term):
      $term = get_term_by('name', $cat_name, 'category');
    endif;

    return ($term) ? $term->term_id : 0;
  }

  public function get_category_id(){
      return $this->lcp_category_id;
  }

  public function get_categories_posts(){
    return $this->lcp_categories_posts;
  }

  /**
   * Load category name and link to the category:
   */
  public function get_category_link(){
    if(($this->lcp_not_empty('catlink') &&
      $this->params['catlink'] == 'yes' ||
      $this->lcp_not_empty('catname') &&
      $this->params['catname'] == 'yes') &&
      $this->lcp_category_id != 0):
      // Check for one id or several:
      $ids = null;
      if (is_array($this->lcp_category_id)){
        $ids = $this->lcp_category_id;
      } else{
        $ids = explode(",", $this->lcp_category_id);
      }

      $link = array();
      // Loop on several categories:
      foreach($ids as $lcp_id){
        $cat_link = get_category_link($lcp_id);
        $cat_title = get_cat_name($lcp_id);

        // Use the category title or 'catlink_string' set by user:
        if ($this->lcp_not_empty('catlink_string')){
          $cat_string = $this->params['catlink_string'];
        } else {
          $cat_string = $cat_title;
        }

        // Do we want the link or just the title?
        if ($this->params['catlink'] == 'yes'){
          $cat_string = '<a href="' . $cat_link . '" title="' . $cat_title . '">' .
            $cat_string .
            $this->get_category_count($lcp_id) .  '</a>';
        }
        array_push($link, $cat_string);
      }
      return implode(", ", $link);
    else:
      return null;
    endif;
  }

  /**
   * Load morelink name and link to the category:
   */
  public function get_morelink(){
    if (!empty($this->params['morelink'])) :
      $href = 'href="' . get_category_link($this->lcp_category_id) . '"';
      $readmore = ($this->params['morelink'] !== '' ? $this->params['morelink'] : 'More posts');
      return '<a ' . $href . ' >' . $readmore . '</a>';
    else:
      return null;
    endif;
  }



  public function get_category_count($id){
    if($this->lcp_not_empty('category_count') && $this->params['category_count'] == 'yes'):
      return ' (' . get_category($id)->category_count . ')';
    endif;
  }

  /**
   * Display custom fields.
   * @see http://codex.wordpress.org/Function_Reference/get_post_custom
   * @param string $custom_key
   * @param int $post_id
   */
  public function get_custom_fields($custom_key, $post_id){
    if( $this->lcp_not_empty('customfield_display') &&
      ( $this->params['customfield_display'] != '') ):
      $lcp_customs = '';

      //Doesn't work for many custom fields when having spaces:
      $custom_key = trim($custom_key);

      //Create array for many fields:
      $custom_array = explode(",", $custom_key);

      //Get post custom fields:
      $custom_fields = get_post_custom($post_id);

      //Loop on custom fields and if there's a value, add it:
      foreach ($custom_array as $user_customfield) :
        if(isset($custom_fields[$user_customfield])):
          $my_custom_field = $custom_fields[$user_customfield];

          if (sizeof($my_custom_field) > 0 ):
            foreach ( $my_custom_field as $key => $value ) :
              if ($this->params['customfield_display_name'] != "no")
                $lcp_customs .= $user_customfield . " : ";
              $lcp_customs .= $value;
            endforeach;
          endif;
        endif;
      endforeach;

      return $lcp_customs;
    else:
      return null;
    endif;
  }

  public function get_comments_count($single){
    if (isset($this->params['comments']) &&
        $this->params['comments'] == 'yes'):
      return ' (' . $single->comment_count . ')';
    else:
      return null;
    endif;
  }

  public function get_author_to_show($single){
    if ($this->params['author'] == 'yes'):
      $lcp_userdata = get_userdata($single->post_author);
      $author_name =  $lcp_userdata->display_name;
      if($this->lcp_not_empty('author_posts_link') &&
        $this->params['author_posts_link'] == 'yes'){
        $link = get_author_posts_url($lcp_userdata->ID);
        return "<a href=" . $link . " title='" . $author_name .
          "'>" . $author_name . "</a>";
      } else {
        return $author_name;
      }
    else:
      return null;
    endif;
  }


  /** Pagination **/
  public function get_page(){
    return $this->page;
  }

  public function get_posts_count(){
    return $this->posts_count;
  }

  public function get_number_posts(){
    return $this->params['numberposts'];
  }

  public function get_instance(){
    return $this->instance;
  }

  public function get_date_to_show($single){
    if ($this->params['date'] == 'yes'):
      //by Verex, great idea!
      return get_the_time($this->params['dateformat'], $single);
    else:
      return null;
    endif;
  }

  public function get_modified_date_to_show($single){
    if ($this->params['date_modified'] == 'yes'):
      return get_the_modified_time($this->params['dateformat'], $single);
    else:
      return null;
    endif;
  }

  public function get_content($single){
    if (isset($this->params['content']) &&
        $this->params['content'] =='yes' &&
        $single->post_content):

      $lcp_content = $single->post_content;
      $lcp_content = apply_filters('the_content', $lcp_content);
      $lcp_content = str_replace(']]>', ']]&gt', $lcp_content);

      if ( preg_match('/[\S\s]+(<!--more(.*?)?-->)[\S\s]+/', $lcp_content, $matches) ):
        if( empty($this->params['posts_morelink']) ):
          $lcp_more = __('Continue reading &rarr;', 'list-category-posts');
        else:
          $lcp_more = '';
        endif;
        $lcp_post_content = explode($matches[1], $lcp_content);
        $lcp_content = $lcp_post_content[0] .
          ' <a href="' . get_permalink($single->ID) . '" title="' . "$lcp_more" . '">' .
          $lcp_more . '</a>';
      endif;

      return $lcp_content;
    else:
      return null;
    endif;
  }

  public function get_excerpt($single){
    if ( !empty($this->params['excerpt']) && $this->params['excerpt']=='yes'){

      if($single->post_excerpt == ("")){
        //No excerpt, generate one:
        $lcp_excerpt = $this->lcp_trim_excerpt($single->post_content);
      }else{
        if(!empty($this->params['excerpt_overwrite']) &&
           $this->params['excerpt_overwrite'] == 'yes'){
          // Excerpt but we want to overwrite it:";
          $lcp_excerpt = $this->lcp_trim_excerpt($single->post_content);
        } else {
          // Bring post excerpt;
          $lcp_excerpt = $this->lcp_trim_excerpt($single->post_excerpt);
        }
      }

      if( strlen($lcp_excerpt) < 1 ){
        $lcp_excerpt = $single->post_title;
      }
      return $lcp_excerpt;
    }
  }

  private function lcp_trim_excerpt($text = ''){
    $excerpt_length = intval($this->params['excerpt_size']);

    $text = strip_shortcodes($text);
    $text = apply_filters('the_excerpt', $text);
    $text = str_replace(']]>',']]&gt;', $text);

    if( $this->lcp_not_empty('excerpt_strip') &&
        $this->params['excerpt_strip'] == 'yes'):
      $text = strip_tags($text);
    endif;

    $words = explode(' ', $text, $excerpt_length + 1);
    if(count($words) > $excerpt_length) :
      array_pop($words);
      array_push($words, '...');
      $text = implode(' ', $words);
    endif;
    return $text;
  }

  /**
   * Get the post Thumbnail
   * @see http://codex.wordpress.org/Function_Reference/get_the_post_thumbnail
   * @param unknown_type $single
   *
   */
  public function get_thumbnail($single, $lcp_thumb_class = null){
    $lcp_thumbnail = null;

    if ($this->params['thumbnail']=='yes'):
      $lcp_thumbnail = '';
      if ( has_post_thumbnail($single->ID) ):

        $available_image_sizes = array_unique(
                                            array_merge(
                                                        get_intermediate_image_sizes(),
                                                        array("thumbnail", "medium", "large", "full")
                                                        )
                                            );
        if ( in_array(
                      $this->params['thumbnail_size'],
                      $available_image_sizes
                      )
             ):
          $lcp_thumb_size = $this->params['thumbnail_size'];
        elseif ($this->params['thumbnail_size']):
          $lcp_thumb_size = explode(",", $this->params['thumbnail_size']);
        else:
          $lcp_thumb_size = 'thumbnail';
        endif;

        $lcp_thumbnail = '<a href="' . esc_url(get_permalink($single->ID)) .
          '" title="' . esc_attr($single->post_title) . '">';

        $lcp_thumbnail .= get_the_post_thumbnail(
          $single->ID,
          $lcp_thumb_size,
          ($lcp_thumb_class != null) ? array('class' => $lcp_thumb_class ) : null
        );
        $lcp_thumbnail .= '</a>';

      # Check for a YouTube video thumbnail
      elseif (
              preg_match("/([a-zA-Z0-9\-\_]+\.|)youtube\.com\/watch(\?v\=|\/v\/)([a-zA-Z0-9\-\_]{11})([^<\s]*)/", $single->post_content, $matches)
              ||
              preg_match("/([a-zA-Z0-9\-\_]+\.|)youtube\.com\/(v\/)([a-zA-Z0-9\-\_]{11})([^<\s]*)/", $single->post_content, $matches)
              ||
              preg_match("/([a-zA-Z0-9\-\_]+\.|)youtube\.com\/(embed)\/([a-zA-Z0-9\-\_]{11})[^<\s]*/", $single->post_content, $matches)
              ):
        $youtubeurl = $matches[0];

        if ($youtubeurl):
          $imageurl = "http://i.ytimg.com/vi/{$matches[3]}/1.jpg";
        endif;

        $lcp_ytimage = '<img src="' . $imageurl . '" alt="' . $single->post_title . '" />';

        if ($lcp_thumb_class != null):
          $thmbn_class = ' class="' . $lcp_thumb_class . '" />';
        $lcp_ytimage = preg_replace("/\>/", $thmbn_class, $lcp_ytimage);
        endif;

        $lcp_thumbnail .= '<a href="' . get_permalink($single->ID).'">' . $lcp_ytimage . '</a>';

      endif;
    endif;
    return $lcp_thumbnail;
  }
}
