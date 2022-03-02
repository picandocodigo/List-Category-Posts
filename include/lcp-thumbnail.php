<?php
class LcpThumbnail{
  // Singleton implementation
  private static $instance = null;

  public static function get_instance(){
    if( !isset( self::$instance ) ){
      self::$instance = new self;
    }
    return self::$instance;
  }

  /**
   * Get the post Thumbnail
   * @see http://codex.wordpress.org/Function_Reference/get_the_post_thumbnail
   * @param unknown_type $single
   *
   */
    public function get_thumbnail($single, $thumbnail, $thumbnail_size, $force_thumbnail, $lcp_thumb_class = null){
      $lcp_thumbnail = null;

      if( $thumbnail == 'yes' ){
        $lcp_thumbnail = '';
  	  $image_sizes = array_unique(
  	    array_merge(
  	  	  get_intermediate_image_sizes(),
  		  array("thumbnail", "medium", "large", "full")
  	    )
  	  );

  	  if( in_array( $thumbnail_size,  $image_sizes ) ){
  	    $lcp_thumb_size = $thumbnail_size;
  	  } elseif( $thumbnail_size ) {
  	    $lcp_thumb_size = explode(",", $thumbnail_size);
  	  } else {
  	    $lcp_thumb_size = 'thumbnail';
  	  }

        if ( has_post_thumbnail($single->ID) ){


          $lcp_thumbnail = '<a href="' . esc_url(get_permalink($single->ID)) .
                         '" title="' . esc_attr($single->post_title) . '">';

          $lcp_thumbnail .= get_the_post_thumbnail(
              $single->ID,
              $lcp_thumb_size,
              array(
                  'alt' => esc_attr($single->post_title),
                  // If we have a class use it, otherwise use default
                  'class' => ( $lcp_thumb_class != null ) ? $lcp_thumb_class : 'lcp_thumbnail'
              )
          );
          $lcp_thumbnail .= '</a>';
        } else {
  		  $content = get_the_content();
  		  $thumb_id = $this->get_first_content_image_id($content);;
  		  if ($thumb_id) {
  			  $lcp_thumbnail = '<a href="' . esc_url(get_permalink($single->ID)) . '" title="' . esc_attr($single->post_title) . '">';
  		      $lcp_thumbnail .= wp_get_attachment_image(
  				$thumb_id,
  				$lcp_thumb_size,
  				array(
  					'alt' => esc_attr($single->post_title),
  					// If we have a class use it, otherwise use default
  					'class' => ( $lcp_thumb_class != null ) ? $lcp_thumb_class : 'lcp_thumbnail'
  				));
  			  $lcp_thumbnail .= '</a>';
  		  } else {
  			  // if thumbnail is requested but not found as featured image, grab first image in the content of the post
  			  if ( ($force_thumbnail === 'yes'|| $force_thumbnail === 'true') && preg_match('~<img[^>]*src\s?=\s?[\'"]([^\'"]*)~i',get_the_content(), $imgMatches)) {
  			    $lcp_thumbnail = '<a href="' . esc_url(get_permalink($single->ID)) .
  							 '" title="' . esc_attr($single->post_title) . '">';

  			    $lcp_thumbnail .= '<img src="' . esc_url($imgMatches[1]) . '" ';
  			    if ( $lcp_thumb_class != null ) {  // thumbnail class passed as parameter to shortcode
  			  	  $lcp_thumbnail .= 'class="' . $lcp_thumb_class . '" ';
  			    }
  			    else { // Otherwise, use this class name
  				  $lcp_thumbnail .= 'class="lcp_thumbnail" ';
  			    }
  			    $lcp_thumbnail .= ' alt="' . esc_attr($single->post_title) . '" /></a>';
  			  }
  			}
  	  }
      } else {
        # Check for a YouTube video thumbnail
        $lcp_thumbnail = $this->check_youtube_thumbnail($single->content);
      }
      return $lcp_thumbnail;
    }

  private function get_first_content_image_id($content) {
  	// set variables
  	global $wpdb;

  	if (isset($content)) {
  	  // look for images in HTML code

  	  preg_match_all('/<img[^>]+>/i', $content, $all_img_tags);

  	  if ($all_img_tags) {
  	    foreach ($all_img_tags[0] as $img_tag) {
  		  // find class attribute and catch its value
  		  preg_match( '/<img.*?class\s*=\s*[\'"]([^\'"]+)[\'"][^>]*>/i', $img_tag, $img_class );
  		    if ($img_class) {
  		    // Look for the WP image id
  		    preg_match( '/wp-image-([\d]+)/i', $img_class[1], $thumb_id );

  		    // if first image id found: check whether is image
  		    if ($thumb_id) {
  		      $img_id = absint( $thumb_id[1] );
  			  // if is image: return its id
  			  if (wp_attachment_is_image($img_id)) {
  			    return $img_id;
  			  }
  		    } // if(thumb_id)
  		  } // if(img_class)

  		  // else: try to catch image id by its url as stored in the database
  		  // find src attribute and catch its value

  		  preg_match( '/<img.*?src\s*=\s*[\'"]([^\'"]+)[\'"][^>]*>/i', $img_tag, $img_src );

  		  if ($img_src) {
  		    // delete optional query string in img src
  		    $url = preg_replace('/([^?]+).*/', '\1', $img_src[1]);

  		    // delete image dimensions data in img file name, just take base name and extension
  		    $url = preg_replace( '/(.+)-\d+x\d+\.(\w+)/', '\1.\2', $url );

  		    // if path is protocol relative then set it absolute
  		    if (0 === strpos($url, '//')) {
  			  $url = $this->defaults[ 'site_protocol' ] . ':' . $url;

  			  // if path is domain relative then set it absolute
  			} elseif (0 === strpos($url, '/')) {
  			  $url = $this->defaults[ 'site_url' ] . $url;
  			}

  			// look up its id in the db
  			$thumb_id = $wpdb->get_var( $wpdb->prepare( "SELECT <code>ID</code> FROM $wpdb->posts WHERE <code>guid</code> = '%s'", $url ) );

  			// if id is available: return it
  			if ($thumb_id) {
  			  return absint($thumb_id);
  			} // if(thumb_id)
    		  } // if(img_src)
  		} // foreach(img_tag)
  	  } // if(all_img_tags)
  	} // if (post content)

      // if nothing found: return 0
      return 0;
    }

  private function check_youtube_thumbnail($content){
    # youtube.com/watch?v=id
    $yt_pattern = '/([a-zA-Z0-9\-\_]+\.|)youtube\.com\/watch(\?v\=|\/v\/)([a-zA-Z0-9\-\_]{11})([^<\s]*)/';
    # youtube.com/v[id]
    $yt_vpattern = "/([a-zA-Z0-9\-\_]+\.|)youtube\.com\/(v\/)([a-zA-Z0-9\-\_]{11})([^<\s]*)/";
    # youtube embedded code
    $yt_epattern = "/([a-zA-Z0-9\-\_]+\.|)youtube\.com\/(embed)\/([a-zA-Z0-9\-\_]{11})[^<\s]*/";

    if (
      preg_match($yt_pattern, $content, $matches) ||
      preg_match($yt_vpattern, $content, $matches) ||
      preg_match($yt_epattern, $content, $matches)
    ) {
      $youtubeurl = $matches[0];

      if ($youtubeurl){
        $imageurl = "http://i.ytimg.com/vi/{$matches[3]}/1.jpg";
      }

      $lcp_ytimage = '<img src="' . $imageurl . '" alt="' . $single->post_title . '" />';

      if ($lcp_thumb_class != null){
        $thmbn_class = ' class="' . $lcp_thumb_class . '" />';
        $lcp_ytimage = preg_replace("/\>/", $thmbn_class, $lcp_ytimage);
      }
      $lcp_thumbnail .= '<a href="' . get_permalink($single->ID).'">' . $lcp_ytimage . '</a>';
    }
  }
}
