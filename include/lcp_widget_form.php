<?php
/**
 * List Category Posts sidebar widget form for Appearance > Widgets.
 * @author fernando@picandocodigo.net
 */

$instance = wp_parse_args( (array) $instance, array(
                'title' => '',
                'categoryid' => '',
                'limit' => '',
                'orderby'=>'',
                'order'=>'',
                'show_date'=>'',
                'show_author'=>'',
                'show_excerpt'=>'',
                'exclude'=>'',
                'excludeposts'=>'',
		'thumbnail' =>'',
                'offset'=>'',
                'show_catlink'=>'' ) );
$title = strip_tags($instance['title']);
$limit = strip_tags($instance['limit']);
$orderby = strip_tags($instance['orderby']);
$order = strip_tags($instance['order']);
$showdate = strip_tags($instance['showdate']);
$showauthor = strip_tags($instance['author']);
$exclude = strip_tags($instance['exclude']);
$excludeposts = strip_tags($instance['excludeposts']);
$offset = strip_tags($instance['offset']);
$showcatlink = strip_tags($instance['catlink']);
$categoryid = strip_tags($instance['categoryid']);
$showexcerpt = strip_tags($instance['excerpt']);
$thumbnail = strip_tags($instance['thumbnail']);
$thumbnail_size = strip_tags($instance['thumbnail_size']);
?>

<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e("Title")?></label>
<br/>
	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" 
	name="<?php echo $this->get_field_name('title'); ?>" type="text" 
	value="<?php echo attribute_escape($title); ?>" />
</p>

<p><label for="<?php echo $this->get_field_id('categoryid'); ?>"><?php _e("Category")?></label>
<br/>
<select id="<?php echo $this->get_field_id('categoryid'); ?>" name="<?php echo $this->get_field_name('categoryid'); ?>">
	<?php 
		$categories=  get_categories();
		foreach ($categories as $cat) :
			$option = '<option value="' . $cat->cat_ID . '" ';
			if ($cat->cat_ID == $categoryid) :
				$option .= ' selected = "selected" ';
			endif;
			$option .=  '">';
			$option .= $cat->cat_name;
			$option .= '</option>';
			echo $option;
		endforeach;
	?>
</select></p>

<p><label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e("Number of posts")?></label>
<br/>
	<input size="2" id="<?php echo $this->get_field_id('limit'); ?>" 
	name="<?php echo $this->get_field_name('limit'); ?>" type="text" 
	value="<?php echo attribute_escape($limit); ?>" />
</p>

<p><label for="<?php echo $this->get_field_id('offset'); ?>">Offset: <br/>
	<input size="2" id="<?php echo $this->get_field_id('offset'); ?>" 
	name="<?php echo $this->get_field_name('offset'); ?>" type="text" 
	value="<?php echo attribute_escape($offset); ?>" />
</label></p>

<p><label for="<?php echo $this->get_field_id('orderby'); ?>">Order by</label> <br/>
	<select  id="<?php echo $this->get_field_id('orderby'); ?>" 
		name="<?php echo $this->get_field_name('orderby'); ?>" type="text" >
		<option value='date'>Date</option>
		<option value='title'>Post title</option>
		<option value='author'>Author</option>
		<option value='rand'>Random</option>
	</select>
</p>

<p><label for="<?php echo $this->get_field_id('order'); ?>">Order:</label><br/>
	<select id="<?php echo $this->get_field_id('order'); ?>" 
		name="<?php echo $this->get_field_name('order'); ?>" type="text">
		<option value='desc'>Descending</option>
		<option value='asc'>Ascending</option>
	</select>
</p>

<p><label for="<?php echo $this->get_field_id('exclude'); ?>">Exclude categories (id's): </label><br/>
	<input id="<?php echo $this->get_field_id('exclude'); ?>"
	name="<?php echo $this->get_field_name('exclude'); ?>" type="text"
	value="<?php echo attribute_escape($exclude); ?>" />
</p>

<p><label for="<?php echo $this->get_field_id('excludeposts'); ?>">Exclude posts (id's): </label><br/>
	<input id="<?php echo $this->get_field_id('excludeposts'); ?>"
	name="<?php echo $this->get_field_name('excludeposts'); ?>" type="text"
	value="<?php echo attribute_escape($excludeposts); ?>" />
</p>


		<label>Show: </label><br/>
        <p>
            <input type="checkbox" <?php checked( (bool) $instance['thumbnail'], true ); ?>
            name="<?php echo $this->get_field_name( 'thumbnail'); ?>" />Thumbnail - size: 
            <select id="<?php echo $this->get_field_id('thumbnail_size'); ?>"
                name="<?php echo $this->get_field_name( 'thumbnail_size' ); ?>" type="text">
                <option value='thumbnail'>thumbnail</option>
                <option value='medium'>medium</option>
                <option value='large'>large</option>
                <option value='full'>full</option>
            </select>
        </p>
        <p>
            <input type="checkbox" <?php checked( (bool) $instance['show_date'], true ); ?>
            name="<?php echo $this->get_field_name( 'show_date' ); ?>" />Date
        </p>
        <p>
            <input type="checkbox" <?php checked( (bool) $instance['show_author'], true ); ?>
            name="<?php echo $this->get_field_name( 'show_author' ); ?>" />Author
        </p>
        <p>
            <input type="checkbox" <?php checked( (bool) $instance['show_catlink'], true ); ?>
            name="<?php echo $this->get_field_name( 'show_catlink' ); ?>" />Link to category
        </p>
        <p>
            <input type="checkbox" <?php checked( (bool) $instance['show_excerpt'], true ); ?>
            name="<?php echo $this->get_field_name( 'show_excerpt' ); ?>" />Excerpt
        </p>

