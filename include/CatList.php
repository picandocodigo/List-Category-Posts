<?php
class CatList{
	private $params = array();
	private $lcp_categories_posts = array();
	private $lcp_category_id = 0;
	private $lcp_category_name = '';
	private $lcp_output = '';
	
	/**
	 * 
	 * Constructor gets the shortcode attributes as parameter
	 * @param unknown_type $atts
	 */
	public function __construct($atts) {
		$this->params = $atts;
		
		$this->lcp_category_id = $this->params['id'];	
		$this->lcp_category_name = $this->params['name'];
		
		//Get the category posts:
		$this->lcp_categories_posts = $this->lcp_get_categories();
	}

	/**
	 * Main function, this is where the flow goes and calls auxiliary functions 
	 */
	private function list_category_posts(){
		$this->load_template();
		
		//Link to the category:
		if ($this->params['catlink'] == 'yes'){
			$cat_link = get_category_link($lcp_category_id);
			$cat_title = get_cat_name($lcp_category_id);
			$lcp_output .= '<a href="' . $cat_link . '" title="' . $cat_title . '">' . $cat_title . '</a>';
		}
		
		return $lcp_output;
	}
	
	private function load_template(){
		$tplFileName = null;
		$possibleTemplates = array(
			// File locations lower in list override others
			TEMPLATEPATH.'/list-category-posts/'.$this->params['template'].'.php',
			STYLESHEETPATH.'/list-category-posts/'.$this->params['template'].'.php'
		);
		foreach ($possibleTemplates as $key => $file) {
			if (is_readable($file)) {
				$tplFileName = $file;
			}
		}
		if ((!empty($tplFileName)) && (is_readable($tplFileName))) {
			require($tplFileName);
		}else{
			// Default template
			$this->lcp_output .= '<ul class="'.$this->params['class'].'">';
			
			foreach ($catposts as $single):
				$lcp_output .= lcp_display_post($single, $this->params);
			endforeach;
			
			$lcp_output .= "</ul>";
		}
	}
	
	/**
	 * Get the categories & posts
	 * @param string $lcp_category_id
	 * @param string $lcp_category_name
	 */
	private function lcp_get_categories(){
		if($this->lcp_category_name != 'default' && $this->lcp_category_id == '0'){
			$lcp_category = 'category_name=' . $this->param['name'];
		}else{
			$lcp_category = 'cat=' . $lcp_category_id;
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
		
		return get_posts($lcp_query);
	}
	
	private function lcp_display_post($single){
		$lcp_display_output = '<li><a href="' . get_permalink($single->ID).'">' . $single->post_title . '</a>';
		if ($this->params['comments'] == yes){
			$lcp_display_output .= ' (';
			$lcp_display_output .=  lcp_comments($single);
			$lcp_display_output .=  ')';
		}
		
		if ($this->params['date']=='yes'){
			$lcp_display_output .= lcp_showdate($single);
		}
		
		if ($this->params['author']=='yes'){
			$lcp_display_output .= " - ".lcp_showauthor($single) . '<br/>';
		}
		
		if($this->params['customfield_display'] != ''){
		  $lcp_display_output .= lcp_display_customfields($this->params['customfield_display'], $single->ID);
		}
		
		if ($this->params['thumbnail']=='yes'){
			$lcp_display_output .= lcp_thumbnail($single);
		}
		
		if ($this->params['content']=='yes' && $single->post_content){
			$lcp_display_output.= lcp_content($single);
		}
		
		if ($this->params['excerpt']=='yes' && !($this->params['content']=='yes' && $single->post_content) ){
			$lcp_display_output .= lcp_excerpt($single);
		}
		
		$lcp_display_output.="</li>";
		return $lcp_display_output;
	}

	/**
	 * toString, display the string
	 */
	public function toString(){
		return $this->lcp_output;
	}
	
	/**
	 **********************************************************************************************************
	 */
	
	public function lcp_comments($single){
		return $single->comment_count;
	}
	
	public function lcp_showauthor($single){
		$lcp_userdata = get_userdata($single->post_author);
		return $lcp_userdata->display_name;
	}
	
	public function lcp_showdate($single){
		return  ' - ' . get_the_time($this->params['dateformat'], $single);//by Verex, great idea!
	}
	
	public function lcp_content($single){
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
		return '<p>' . $lcp_content . '</p>';
	}
	
	
	public function lcp_excerpt($single){
		if($single->post_excerpt){
			return '<p>' . $single->post_excerpt . '</p>';
		}
		$lcp_excerpt = strip_tags($single->post_content);
		if ( post_password_required($post) ) {
			$lcp_excerpt = __('There is no excerpt because this is a protected post.');
			return $lcp_excerpt;
		}
		if (strlen($lcp_excerpt) > 255) {
			$lcp_excerpt = substr($lcp_excerpt, 0, 252) . '...';
		}
		return '<p>' . $lcp_excerpt . '</p>';
	}
	
	/**
	 * Get the post Thumbnail
	 * @see http://codex.wordpress.org/Function_Reference/get_the_post_thumbnail
	 * @param unknown_type $single
	 */
	public function lcp_thumbnail($single){
		$lcp_thumbnail = '';
		if ( has_post_thumbnail($single->ID) ) {
			$lcp_thumbnail = get_the_post_thumbnail($single->ID);
		}
		return $lcp_thumbnail;
	}
	
	/**
	 * Display custom fields.
	 * @see http://codex.wordpress.org/Function_Reference/get_post_custom
	 * @param string $custom_key
	 * @param int $post_id
	 */
	public function lcp_display_customfields($custom_key, $post_id){
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