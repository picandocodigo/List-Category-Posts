<?php
/**
 * The CatList object gets the info for the CatListDisplayer to show.
 * Each shortcode appearence is an instance of this class.
 * @author fernando@picandocodigo.nets
 */

class CatList{
    private $params = array();
    private $lcp_categories_posts = array();
    private $lcp_category_id = 0;
    private $lcp_category_name = '';

    /**
     * Constructor gets the shortcode attributes as parameter
     * @param array $atts
     */
    public function __construct($atts) {
        $this->params = $atts;
        //Get the category posts:
         $this->lcp_set_categories();
    }

    /**
     * Get the categories & posts
     */
    private function lcp_set_categories(){
        if($this->params['name'] != '' && $this->params['id'] == '0'){
            $this->lcp_category_name = $this->params['name'];
            $lcp_category = 'category_name=' . $this->lcp_category_name;
            //$this->lcp_category_id = ;
        }else{
            $this->lcp_category_id = $this->params['id'];
            $lcp_category = 'cat=' . $this->lcp_category_id;
        }
	
        //Build the query for get_posts()
        $lcp_query = $lcp_category.'&numberposts=' . $this->params['numberposts'] .
                                '&orderby=' . $this->params['orderby'] .
                                '&order=' . $this->params['order'] .
                                '&exclude=' . $this->params['excludeposts'] .
                                '&tag=' . $this->params['tags'] .
                                '&offset=' . $this->params['offset'];

        // Post type and post parent:
        if($this->params['post_type']): $lcp_query .= '&post_type=' . $this->params['post_type']; endif;
        if($this->params['post_parent']): $lcp_query .= '&post_parent=' . $this->params['post_parent']; endif;

        // Custom fields 'customfield_name' & 'customfield_value' should both be defined
        if($this->params['customfield_name']!='' && $this->params['customfield_value'] != ''):
                $lcp_query .= '&meta_key=' . $this->params['customfield_name'] . '&meta_value=' . $this->params['customfield_value'];
        endif;
        $this->lcp_categories_posts = get_posts($lcp_query);
    }

    public function get_categories_posts(){
        return $this->lcp_categories_posts;
    }
	
    /**
     * Load category name and link to the category:
     */
    public function get_category_link(){
        $cat_link = get_category_link($this->lcp_category_id);
        $cat_title = get_cat_name($this->lcp_category_id);
        return '<a href="' . $cat_link . '" title="' . $cat_title . '">' . $cat_title . '</a>';
    }

    /**
     * Display custom fields.
     * @see http://codex.wordpress.org/Function_Reference/get_post_custom
     * @param string $custom_key
     * @param int $post_id
     */
    public function get_custom_fields($custom_key, $post_id){
        if($this->params['customfield_display'] != ''){
            $lcp_customs = '';
            //Doesn't work for many when having spaces:
            $custom_key = trim($custom_key);
            //Create array for many fields:
            $custom_array = explode(",", $custom_key);
            //Get post custom fields:
            $custom_fields = get_post_custom($post_id);
            //Loop on custom fields and if there's a value, add it:
            foreach ($custom_array as $something){
                $my_custom_field = $custom_fields[$something];
                if (sizeof($my_custom_field) > 0 ):
                    foreach ( $my_custom_field as $key => $value ){
                        $lcp_customs .= "<div class=\"lcp-customfield\">" . $something. " : " . $value . "</div>";
                    }
                endif;
            }
            return $lcp_customs;
        }
    }

    public function get_comments_count($single){
        if ($this->params['comments'] == 'yes'){
                return ' (' . $single->comment_count . ')';
        }
    }

    public function get_author_to_show($single){
        if ($this->params['author']=='yes'){
            $lcp_userdata = get_userdata($single->post_author);
            return $lcp_userdata->display_name;
        }
    }



    public function get_date_to_show($single){
        if ($this->params['date']=='yes'){
            //by Verex, great idea!
            return  get_the_time($this->params['dateformat'], $single);
        }
    }

    public function get_content($single){
        if ($this->params['content']=='yes' && $single->post_content){
            $lcp_content = $single->post_content;
            //Need to put some more thought on this!
            //Added to stop a post with catlist to display an infinite loop of catlist shortcode parsing
            /*if (preg_match("/\[catlist.*\]/", $lcp_content, $regmatch)){
                    foreach ($regmatch as $match){
                            $lcp_content = str_replace($match, '(...)',$lcp_content);
                    }
            }*/
            $lcp_content = apply_filters('the_content', $lcp_content); // added to parse shortcodes
            $lcp_content = str_replace(']]>', ']]&gt', $lcp_content); // added to parse shortcodes
            return $lcp_content;
       }
    }

    public function get_excerpt($single){
        if ($this->params['excerpt']=='yes' && !($this->params['content']=='yes' && $single->post_content) ){
            if($single->post_excerpt){
                    return $single->post_excerpt;
            }
            $lcp_excerpt = strip_tags($single->post_content);
            if ( post_password_required($single) ) {
                    $lcp_excerpt = __('There is no excerpt because this is a protected post.');
                    return $lcp_excerpt;
            }
            if (strlen($lcp_excerpt) > 255) {
                    $lcp_excerpt = substr($lcp_excerpt, 0, 252) . '...';
            }
            return $lcp_excerpt;
        }
    }

    /**
     * Get the post Thumbnail
     * @see http://codex.wordpress.org/Function_Reference/get_the_post_thumbnail
     * @param unknown_type $single
     */
    public function get_thumbnail($single){
        if ($this->params['thumbnail']=='yes'){
            $lcp_thumbnail = '';
            if ( has_post_thumbnail($single->ID) ) {
                    $lcp_thumbnail = get_the_post_thumbnail($single->ID);
            }
            return $lcp_thumbnail;
        }
    }


}