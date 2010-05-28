<?php
/* Copyright 2008-2010  Fernando Briano  (email : fernando@picandocodigo.net)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

$instance = wp_parse_args( (array) $instance, array( 'title' => '',
														'categoryid' => '',
														'limit' => '',
														'orderby'=>'',
														'order'=>'',
														'date'=>'',
														'author'=>'',
														'excerpt'=>'',
														'exclude'=>'',
														'excludeposts'=>'',
														'offset'=>'',
														'catlink'=>'' ) );
		$title = strip_tags($instance['title']);
		$limit = strip_tags($instance['limit']);
		$orderby = strip_tags($instance['orderby']);
		$order = strip_tags($instance['order']);
		$date = strip_tags($instance['date']);
		$author = strip_tags($instance['author']);
		$exclude = strip_tags($instance['exclude']);
		$excludeposts = strip_tags($instance['excludeposts']);
		$offset = strip_tags($instance['offset']);
		$catlink = strip_tags($instance['catlink']);
		$categoryid = strip_tags($instance['categoryid']);
		
?>
<?php //var_dump($instance);?>
<p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <br/>
	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" 
	name="<?php echo $this->get_field_name('title'); ?>" type="text" 
	value="<?php echo attribute_escape($title); ?>" />
</label></p>

<p><label for="<?php echo $this->get_field_id('categoryid'); ?>">Category: <br/>
<select id="<?php echo $this->get_field_id('categoryid'); ?>" name="<?php echo $this->get_field_name('categoryid'); ?>">
	<?php 
		$categories=  get_categories();
		foreach ($categories as $cat) :
			$option = '<option value="' . $cat->cat_ID;
			if ($cat->cat_ID == attribute_escape($categoryid)) :
				$option .= ' selected = "selected" ';
			endif;
			$option .=  '">';
			$option .= $cat->cat_name;
			$option .= '</option>';
			echo $option;
		endforeach;
	?>
</select></p>

<p><label for="<?php echo $this->get_field_id('limit'); ?>">Number of posts: <br/>
	<input size="2" id="<?php echo $this->get_field_id('limit'); ?>" 
	name="<?php echo $this->get_field_name('limit'); ?>" type="text" 
	value="<?php echo attribute_escape($limit); ?>" />
</label></p>

<p><label for="<?php echo $this->get_field_id('offset'); ?>">Offset: <br/>
	<input size="2" id="<?php echo $this->get_field_id('offset'); ?>" 
	name="<?php echo $this->get_field_name('offset'); ?>" type="text" 
	value="<?php echo attribute_escape($offset); ?>" />
</label></p>

<p><label for="<?php echo $this->get_field_id('order'); ?>">Order: <br/>
<select  id="<?php echo $this->get_field_id('orderby'); ?>" 
	name="<?php echo $this->get_field_name('orderby'); ?>" type="text" />
	<option value='date'>Date</option>
	<option value='title'>Post title</option>
	<option value='author'>Author</option>
	<option value='rand'>Random</option>
</select></p>

<p><label for="<?php echo $this->get_field_id('order'); ?>">Order: <br/>
<select id="<?php echo $this->get_field_id('order'); ?>" 
	name="<?php echo $this->get_field_name('order'); ?>" type="text" />
	<option value='desc'>Descending</option>
	<option value='asc'>Ascending</option>
</select></p>

<p><label for="<?php echo $this->get_field_id('exclude'); ?>">Exclude categories (id's): <br/>
	<input id="<?php echo $this->get_field_id('exclude'); ?>"
	name="<?php echo $this->get_field_name('exclude'); ?>" type="text"
	value="<?php echo attribute_escape($exclude); ?>" />
</label></p>

<p><label for="<?php echo $this->get_field_id('excludeposts'); ?>">Exclude posts (id's): <br/>
	<input id="<?php echo $this->get_field_id('excludeposts'); ?>"
	name="<?php echo $this->get_field_name('excludeposts'); ?>" type="text"
	value="<?php echo attribute_escape($excludeposts); ?>" />
</label></p>

<p>
		<label>Show: </label><br/>
		<input type="radio" 
			<?php if ($date == 'on' ) : echo ' checked = "checked" '; endif; ?>
			name="<?php echo $this->get_field_name('date'); ?>" 
			value="<?php echo attribute_escape($date); ?>">Date<br>
		<input type="radio" 
			<?php if ($author == 'on' ) : echo ' checked = "checked" '; endif; ?>
			name="<?php echo $this->get_field_name('author'); ?>" 
			value="<?php echo attribute_escape($author); ?>">Author<br>
		<input type="radio" 
			<?php if ($catlink == 'on' ) : echo ' checked = "checked" '; endif; ?>
			name="<?php echo $this->get_field_name('catlink'); ?>" 
			value="<?php echo attribute_escape($catlink); ?>">Link to category<br>
		<input type="radio" 
			<?php if ($excerpt == 'on' ) : echo ' checked = "checked" '; endif; ?>
			name="<?php echo $this->get_field_name('excerpt'); ?>" 
			value="<?php echo attribute_escape($excerpt); ?>">Excerpt<br>
</p>
