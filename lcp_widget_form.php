<?php
	$options = get_option("lcp_widget_options");
	if (!is_array( $options)){
		$options = array(
		'lcp_widget_categoryid' => '1',
		'lcp_widget_limit' => '5',
		'lcp_widget_orderby' => 'date',
		'lcp_widget_order' => 'asc'
		);	
	}
?>
<p>Category:<br/>
<select name="lcp_widget_categoryid">
	<?php 
		$categories=  get_categories(); 
		foreach ($categories as $cat) {
				$option = '<option value="'.$cat->cat_ID.'">';
				$option .= $cat->cat_name;
				$option .= '</option>';
				echo $option;
		}
	?>
</select></p>

<p>Number of posts:<br/>
<input type='text' name='lcp_widget_limit'></p>
<p>Order By:<br/>
<select name='lcp_widget_orderby'>
	<option value='date'>Date</option>
	<option value='title'>Post title</option>
	<option value='author'>Author</option>
	<option value='rand'>Random</option>
</select></p>
<p>Order:<br/>
<select name='lcp_widget_order'>
	<option value='desc'>Descending</option>
	<option value='asc'>Ascending</option>
</select></p>

<?php
if ($_POST['lcp_widget_submit']){
	$options['lcp_widget_categoryid']=htmlspecialchars($_POST['lcp_widget_categoryid']);
	$options['lcp_widget_limit']=htmlspecialchars($_POST['lcp_widget_limit']);
	$options['lcp_widget_orderby']=htmlspecialchars($_POST['lcp_widget_orderby']);
	$options['lcp_widget_order']=htmlspecialchars($_POST['lcp_widget_order']);

	update_option("lcp_widget_categoryid", $options['lcp_widget_categoryid']);
	update_option("lcp_widget_limit", $options['lcp_widget_limit']);
	update_option("lcp_widget_orderby", $options['lcp_widget_orderby']);
	update_option("lcp_widget_order", $options['lcp_widget_order']);
}
?>
	<input type="hidden" id="lcp_widget_submit" name="lcp_widget_submit" value="1" />
