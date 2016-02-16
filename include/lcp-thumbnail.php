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
  public function get_thumbnail($single, $thumbnail, $thumbnail_size, $lcp_thumb_class = null){
    $lcp_thumbnail = null;

    if( $thumbnail == 'yes' ){
      $lcp_thumbnail = '';

      if ( has_post_thumbnail($single->ID) ){
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

        $lcp_thumbnail = '<a href="' . esc_url(get_permalink($single->ID)) .
          '" title="' . esc_attr($single->post_title) . '">';

        $lcp_thumbnail .= get_the_post_thumbnail(
          $single->ID,
          $lcp_thumb_size,
          ( $lcp_thumb_class != null ) ? array( 'class' => $lcp_thumb_class ) : null
        );
        $lcp_thumbnail .= '</a>';
      }
      else {  // if thumbnail is requested but not found as featured image, grab first image in the content of the 
          if (preg_match('~<img[^>]*src\s?=\s?[\'"]([^\'"]*)~i',get_the_content(), $imgMatches)) {
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
    } else {
      # Check for a YouTube video thumbnail
      $lcp_thumbnail = $this->check_youtube_thumbnail($single->content);
    }
    return $lcp_thumbnail;
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
    ){
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
    }
  }
}