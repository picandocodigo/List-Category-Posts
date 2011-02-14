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

    /**
     *  This function should be overriden for template system.
     * @param post $single
     * @param HTML tag to display $tag
     * @return string
     */
    private function lcp_build_post($single, $tag){
        $lcp_display_output = '<'. $tag . '>' . $this->get_post_title($single);

        $lcp_display_output .= $this->get_comments($single);

        $lcp_display_output .= ' ' . $this->get_date($single);

        $lcp_display_output .= '<br/>' . $this->get_author($single) . '<br/>';

        $lcp_display_output .= $this->get_custom_fields($this->params['customfield_display'], $single->ID);

        $lcp_display_output .= $this->get_thumbnail($single);

        $lcp_display_output .= $this->get_content($single);

        $lcp_display_output .= $this->get_excerpt($single);

        $lcp_display_output .= '</' . $tag . '>';

        return $lcp_display_output;
    }

    /**
     * Auxiliary functions for templates
     */
    private function get_author($single){
        return $this->catlist->get_author_to_show($single);
    }

    private function get_comments($single){
        return $this->catlist->get_comments_count($single);
    }

    private function get_content($single){
        return $this->catlist->get_content($single);
    }

    private function get_custom_fields($custom_key, $post_id){
        return $this->catlist->get_custom_fields($custom_key, $post_id);
    }

    private function get_date($single){
        return $this->catlist->get_date_to_show($single);
    }

    private function get_excerpt($single){
        return $this->catlist->get_excerpt($single);
    }

    private function get_thumbnail($single){
        return $this->catlist->get_thumbnail($single);
    }

    private function get_post_title($single){
        return '<a href="' . get_permalink($single->ID).'">' . $single->post_title . '</a>';
    }



}