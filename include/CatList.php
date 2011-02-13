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

}