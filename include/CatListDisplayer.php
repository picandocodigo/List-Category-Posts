<?php
/**
 * This is an auxiliary class to help display the info on your CatList.php instance.
 *
 * @author fernando@picandocodigo.nets
 */
require_once 'CatList.php';

class CatListDisplayer {
    private $catlist;
    private $params = array();
    private $lcp_output;

    public function __construct($atts) {
        $this->params = $atts;
        $this->catlist = new CatList($atts);
        $this->template();
    }

    public function display(){
        return $this->lcp_output;
    }

    /**
     * Template code
     */
    private function template(){
        $tplFileName = null;
        $possibleTemplates = array(
                // File locations lower in list override others
                TEMPLATEPATH.'/list-category-posts/'.$this->params['template'].'.php',
                STYLESHEETPATH.'/list-category-posts/'.$this->params['template'].'.php'
        );

        foreach ($possibleTemplates as $key => $file) {
                if ( is_readable($file) ) {
                        $tplFileName = $file;
                }
        }
        if ( !empty($tplFileName) && is_readable($tplFileName) ) {
                require($tplFileName);
        }else{
            switch($this->params['template']){
                case "default":
                    $this->build_output('ul');
                    break;
                case "div":
                    $this->build_output('div');
                    break;
                default:
                    $this->build_output('ul');
                    break;
            }
        }
    }
    
    private function build_output($tag){
        $this->lcp_output .= '<' . $tag . ' class="'.$this->params['class'].'">';
        $inner_tag = ($tag == 'ul') ? 'li' : 'p';
        //Posts loop
        foreach ($this->catlist->get_categories_posts() as $single):
                $this->lcp_output .= $this->lcp_build_post($single, $inner_tag);
        endforeach;

        $this->lcp_output .= '</' . $tag . '>';
    }

    private function lcp_build_post($single, $tag){
        $lcp_display_output = '<'. $tag . '><a href="' . get_permalink($single->ID).'">' . $single->post_title . '</a>';
        $lcp_display_output .=  $this->get_comments_count($single);
        $lcp_display_output .= $this->get_date_to_show($single);       
        $lcp_display_output .= $this->get_author_to_show($single);
        $lcp_display_output .= $this->catlist->get_custom_fields($this->params['customfield_display'], $single->ID);
        $lcp_display_output .= $this->get_thumbnail($single);
        $lcp_display_output .= $this->get_content($single);
        $lcp_display_output .= $this->get_excerpt($single);
        $lcp_display_output .= '</' . $tag . '>';

        return $lcp_display_output;
    }

    private function get_comments_count($single){
        if ($this->params['comments'] == 'yes'){
                return ' (' . $single->comment_count . ')';
        }
    }

    private function get_author_to_show($single){
        if ($this->params['author']=='yes'){
            $lcp_userdata = get_userdata($single->post_author);
            return $lcp_userdata->display_name;
        }
    }

    private function get_date_to_show($single){
        if ($this->params['date']=='yes'){
            //by Verex, great idea!
            return  get_the_time($this->params['dateformat'], $single);
        }
    }

    private function get_content($single){
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

    private function get_excerpt($single){
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
            $lcp_excerpt = apply_filters('the_content', $lcp_excerpt); // added to parse shortcodes
            $lcp_excerpt = str_replace(']]>', ']]&gt', $lcp_excerpt); // added to parse shortcodes
            return $lcp_excerpt;
        }
    }

    /**
     * Get the post Thumbnail
     * @see http://codex.wordpress.org/Function_Reference/get_the_post_thumbnail
     * @param unknown_type $single
     */
    private function get_thumbnail($single){
        if ($this->params['thumbnail']=='yes'){
            $lcp_thumbnail = '';
            if ( has_post_thumbnail($single->ID) ) {
                    $lcp_thumbnail = get_the_post_thumbnail($single->ID);
            }
            return $lcp_thumbnail;
        }
    }

}