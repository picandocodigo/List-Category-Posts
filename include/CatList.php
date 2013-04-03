<?php
  /**
   * The CatList object gets the info for the CatListDisplayer to show.
   * Each time you use the shortcode, you get an instance of this class.
   * @author fernando@picandocodigo.net
   */

class CatList{
  private $params = array();
  private $lcp_category_id = 0;
  private $category_param;

  /**
   * Constructor gets the shortcode attributes as parameter
   * @param array $atts
   */
  public function __construct($atts) {
    $this->params = $atts;
    //Get the category posts:
    $this->get_lcp_category();
    $this->set_lcp_parameters();
  }



  /**
   * Order the parameters and query the DB for posts
   */
  private function set_lcp_parameters(){
    if (is_array($this->lcp_category_id)):
      $args = array('category__and' => $this->lcp_category_id);
    else:
      $args = array('cat'=> $this->lcp_category_id);
    endif;

    $args = array_merge($args, array(
      'numberposts' => $this->params['numberposts'],
      'orderby' => $this->params['orderby'],
      'order' => $this->params['order'],
      'offset' => $this->params['offset']
    ));

    //Exclude
    if( $this->lcp_not_empty('excludeposts') ):
      $args['exclude'] = $this->params['excludeposts'];
      if (strpos($args['exclude'], 'this') !== FALSE) :
        $args['exclude'] = $args['exclude'] .
          ",". $this->lcp_get_current_post_id();
      endif;
    endif;

    // Post type, status, parent params:
    if($this->lcp_not_empty('post_type')):
      $args['post_type'] = $this->params['post_type'];
    endif;

    if($this->lcp_not_empty('post_status')):
      $args['post_status'] = $this->params['post_status'];
    endif;

    if($this->lcp_not_empty('post_parent')):
      $args['post_parent'] = $this->params['post_parent'];
    endif;

    if($this->lcp_not_empty('year')):
      $args['year'] = $this->params['year'];
    endif;

    if($this->lcp_not_empty('monthnum')):
      $args['monthnum'] = $this->params['monthnum'];
    endif;

    if($this->lcp_not_empty('search')):
      $args['s'] = $this->params['search'];
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
      $args['post_status'] = array('publish','private');
    endif;

    if ( $this->lcp_not_empty('exclude_tags') ):
      $excluded_tags = explode(",", $this->params['exclude_tags']);
      $tag_ids = array();
      foreach ( $excluded_tags as $excluded):
        $tag_ids[] = get_term_by('slug', $excluded, 'post_tag')->term_id;
      endforeach;
      $args['tag__not_in'] = $tag_ids;
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

    $this->lcp_categories_posts = get_posts($args);
  }

  private function lcp_not_empty($param){
    if ( ( isset($this->params[$param]) ) &&
         ( !empty($this->params[$param]) ) &&
         ( $this->params[$param] != '0' ) &&
         ( $this->params[$param] != '') ) :
      return true;
    else:
      return false;
    endif;
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
        $this->lcp_category_id = explode("+", $this->params['id']);
      else:
        $this->lcp_category_id = $this->params['id'];
      endif;
    endif;
  }

  public function lcp_get_current_category(){
    $category = get_category( get_query_var( 'category' ) );
    if(isset($category->errors) && $category->errors["invalid_term"][0] == "Empty Term"):
      global $post;
      $categories = get_the_category($post->ID);
      return $categories[0]->cat_ID;
    endif;
    return $category->cat_ID;
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
    if($this->params['catlink'] == 'yes' && $this->lcp_category_id != 0):
      $cat_link = get_category_link($this->lcp_category_id);
      $cat_title = get_cat_name($this->lcp_category_id);

      return '<a href="' . $cat_link . '" title="' . $cat_title . '">' .
        ($this->params['catlink_string'] !== '' ? $this->params['catlink_string'] : $cat_title) . '</a>';
    else:
      return null;
    endif;
  }

  /**
   * Display custom fields.
   * @see http://codex.wordpress.org/Function_Reference/get_post_custom
   * @param string $custom_key
   * @param int $post_id
   */
  public function get_custom_fields($custom_key, $post_id){
    if($this->params['customfield_display'] != ''):
      $lcp_customs = '';

      //Doesn't work for many when having spaces:
      $custom_key = trim($custom_key);

      //Create array for many fields:
      $custom_array = explode(",", $custom_key);

      //Get post custom fields:
      $custom_fields = get_post_custom($post_id);

      //Loop on custom fields and if there's a value, add it:
      foreach ($custom_array as $something) :
        $my_custom_field = $custom_fields[$something];
        if (sizeof($my_custom_field) > 0 ):
          foreach ( $my_custom_field as $key => $value ) :
            $lcp_customs .= "<div class=\"lcp-customfield\">" .
              $something. " : " . $value . "</div>";
          endforeach;
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
    if ($this->params['author']=='yes'):
      $lcp_userdata = get_userdata($single->post_author);
      return $lcp_userdata->display_name;
    else:
      return null;
    endif;
  }



  public function get_date_to_show($single){
    if ($this->params['date']=='yes'):
      //by Verex, great idea!
      return  get_the_time($this->params['dateformat'], $single);
    else:
      return null;
    endif;
  }

  public function get_content($single){
    if (isset($this->params['content']) &&
        $this->params['content'] =='yes' &&
        $single->post_content):

      $lcp_content = $single->post_content;
      /* Need to put some more thought on this!
       * Added to stop a post with catlist to display an infinite loop of
       * catlist shortcode parsing
       * added to parse shortcodes
       */
      $lcp_content = apply_filters('the_content', $lcp_content);
      $lcp_content = str_replace(']]>', ']]&gt', $lcp_content);
      return $lcp_content;
    else:
      return null;
    endif;
  }

  public function get_excerpt($single){
    if ($this->params['excerpt']=='yes' &&
        !($this->params['content']=='yes' &&
        $single->post_content) ):

      if($single->post_excerpt && $this->params['excerpt_overwrite'] != 'yes'):
        return $lcp_excerpt = $this->lcp_trim_excerpt($single->post_excerpt);
      endif;

      return $lcp_excerpt = $this->lcp_trim_excerpt($single->post_content);
    else:
      return null;
    endif;
  }

  private function lcp_trim_excerpt($text = ''){
    $excerpt_length = intval($this->params['excerpt_size']);

    $text = strip_shortcodes($text);
    $text = apply_filters('the_content', $text);
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
    if ($this->params['thumbnail']=='yes'):
      $lcp_thumbnail = '';
      if ( has_post_thumbnail($single->ID) ):

        if ( in_array( $this->params['thumbnail_size'],
                       array('thumbnail', 'medium', 'large', 'full')
             )):
          $lcp_thumb_size = $this->params['thumbnail_size'];

        elseif ($this->params['thumbnail_size']):
          $lcp_thumb_size = explode(",", $this->params['thumbnail_size']);
        else:
          $lcp_thumb_size = 'thumbnail';
        endif;

        $lcp_thumbnail = '<a href="' . get_permalink($single->ID).'">';

        $lcp_thumbnail .= get_the_post_thumbnail(
          $single->ID,
          $lcp_thumb_size,
          ($lcp_thumb_class != null) ? array('class' => $lcp_thumb_class ) : null
        );
        $lcp_thumbnail .= '</a>';
      endif;
      return $lcp_thumbnail;

    else:
      return null;
    endif;
  }
}