<?php
//Sidebar Widget file

function lcp_load_widget() {
	if (function_exists('register_sidebar_widget')) {
		register_sidebar_widget('List category posts', 'lcp_widget');
		register_widget_control('List category posts', 'lcp_widget_options', 300, 200 );
	}
}

function lcp_widget(){//Display
	$id = get_option("lcp_widget_categoryid");
	$limit = get_option("lcp_widget_limit");
	$orderby=get_option("lcp_widget_orderby");
	$order=get_option("lcp_widget_order");
	$result = '<ul class="lcp_catlist">';
	$catposts = get_posts('category='.$id.'&numberposts='.$limit.'&orderby='.$orderby.'&order='.$order);
	foreach($catposts as $single):
		$result.='<li><a href="'.get_permalink($single->ID).'">'.$single->post_title.'</a></li>';
	endforeach;
	$result .= "</ul>";
	echo $result;
}

function lcp_widget_options(){
	include('lcp_widget_form.php');
} 

?>
