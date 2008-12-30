<?php
//Sidebar Widget file

function lcp_load_widget() {
  if (function_exists('register_sidebar_widget')) {
    register_sidebar_widget('List category posts', 'lcp_widget');
    register_widget_control('List category posts', 'lcp_widget_options', 300, 200 );
  }
}

function lcp_widget(){//Display
  $lcp_title = get_option("lcp_widget_title");
  $lcp_id = get_option("lcp_widget_categoryid");
  $lcp_limit = get_option("lcp_widget_limit");
  $lcp_orderby=get_option("lcp_widget_orderby");
  $lcp_order=get_option("lcp_widget_order");
  $lcp_result = '<h2>'.$lcp_title.'</h2><ul class="lcp_catlist">';
  $lcp_catposts = get_posts('category='.$lcp_id.'&numberposts='.$lcp_limit.'&orderby='.$lcp_orderby.'&order='.$lcp_order);
  
foreach($lcp_catposts as $lcp_single):
  $lcp_result.='<li><a href="'.get_permalink($lcp_single->ID).'">'.$lcp_single->post_title.'</a></li>';
  endforeach;
  $lcp_result .= "</ul>";
  echo $lcp_result;
}

function lcp_widget_options(){
  include('lcp_widget_form.php');
} 

?>
