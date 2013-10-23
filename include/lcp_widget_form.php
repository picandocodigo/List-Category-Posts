<?php
  /**
   * List Category Posts sidebar widget form for Appearance > Widgets.
   * @author fernando@picandocodigo.net
   */
  $default = array (
                    'title' => '',
                    'categoryid' => '',
                    'limit' => '',
                    'orderby'=>'',
                    'order'=>'',
                    'show_date'=>'',
                    'show_author'=>'',
                    'show_excerpt'=>'',
                    'excerpt_size' =>'',
                    'exclude'=>'',
                    'excludeposts'=>'',
                    'thumbnail' =>'',
                    'thumbnail_size' =>'',
                    'offset'=>'',
                    'show_catlink'=>'',
                    'morelink' =>''
                    );
  $instance = wp_parse_args( (array) $instance, $default);

  $title = strip_tags($instance['title']);
  $limit = strip_tags($instance['limit']);
  $orderby = strip_tags($instance['orderby']);
  $order = strip_tags($instance['order']);
  $showdate = strip_tags($instance['show_date']);
  $showauthor = strip_tags($instance['show_author']);
  $exclude = strip_tags($instance['exclude']);
  $excludeposts = strip_tags($instance['excludeposts']);
  $offset = strip_tags($instance['offset']);
  $showcatlink = strip_tags($instance['show_catlink']);
  $categoryid = strip_tags($instance['categoryid']);
  $showexcerpt = strip_tags($instance['show_excerpt']);
  $excerptsize = strip_tags($instance['excerpt_size']);
  $thumbnail = strip_tags($instance['thumbnail']);
  $thumbnail_size = strip_tags($instance['thumbnail_size']);
  $morelink = strip_tags($instance['morelink']);

?>

<p>
  <label for="<?php echo $this->get_field_id('title'); ?>">
    <?php _e("Title", 'list-category-posts')?>
  </label>
  <br/>
  <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
    name="<?php echo $this->get_field_name('title'); ?>" type="text"
    value="<?php echo esc_attr($title); ?>" />
</p>

<p>
  <label for="<?php echo $this->get_field_id('categoryid'); ?>">
    <?php _e("Category", 'list-category-posts')?>
  </label>
  <br/>
  <select id="<?php echo $this->get_field_id('categoryid'); ?>" name="<?php echo $this->get_field_name('categoryid'); ?>">
    <?php
      $categories=  get_categories();
      $option = '<option value="-1"';
      if ($categoryid == -1) :
        $option .= ' selected = "selected" ';
      endif;
      $option .= '">' . "Current category" . '</option>';
      echo $option;

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
  </select>
</p>

<p>
  <label for="<?php echo $this->get_field_id('limit'); ?>">
    <?php _e("Number of posts", 'list-category-posts')?>
  </label>
  <br/>
  <input size="2" id="<?php echo $this->get_field_id('limit'); ?>"
    name="<?php echo $this->get_field_name('limit'); ?>" type="text"
    value="<?php echo esc_attr($limit); ?>" />
</p>

<p>
  <label for="<?php echo $this->get_field_id('offset'); ?>">
    <?php _e("Offset", 'list-category-posts')?>: <br/>
      <input size="2" id="<?php echo $this->get_field_id('offset'); ?>"
        name="<?php echo $this->get_field_name('offset'); ?>" type="text"
        value="<?php echo esc_attr($offset); ?>" />
  </label>
</p>

<p>
  <label for="<?php echo $this->get_field_id('orderby'); ?>">
    <?php _e("Order by", 'list-category-posts')?>
  </label> <br/>
    <select  id="<?php echo $this->get_field_id('orderby'); ?>"
      name="<?php echo $this->get_field_name('orderby'); ?>" type="text" >
      <?php $lcp_orders = array("date" => __("Date", "list-category-posts"),
                                "title" => __("Post title", "list-category-posts"),
                                "author" => __("Author", "list-category-posts"),
                                "rand" => __("Random", "list-category-posts"));
      foreach ($lcp_orders as $key=>$value):
        $option = '<option value="' . $key . '" ';
        if ($orderby == $key):
          $option .= ' selected = "selected" ';
        endif;
        $option .=  '>';
        echo $option;
        _e($value, 'list-category-posts');
        echo '</option>';
      endforeach;
    ?>
  </select>
</p>

<p>
  <label for="<?php echo $this->get_field_id('order'); ?>">
    <?php _e("Order", 'list-category-posts')?>
  </label>
  <br/>
  <select id="<?php echo $this->get_field_id('order'); ?>"
    name="<?php echo $this->get_field_name('order'); ?>" type="text">
    <option value='desc' <?php if($order == 'desc'): echo "selected: selected"; endif;?>>
      <?php _e("Descending", 'list-category-posts')?>
    </option>
    <option value='asc' <?php if($order == 'asc'): echo "selected: selected"; endif; ?>>
      <?php _e("Ascending", 'list-category-posts')?>
    </option>
  </select>
</p>

<p>
  <label for="<?php echo $this->get_field_id('exclude'); ?>">
    <?php _e("Exclude categories (id's)", 'list-category-posts')?>
  </label>
  <br/>
  <input id="<?php echo $this->get_field_id('exclude'); ?>"
    name="<?php echo $this->get_field_name('exclude'); ?>" type="text"
    value="<?php echo esc_attr($exclude); ?>" />
</p>

<p>
  <label for="<?php echo $this->get_field_id('excludeposts'); ?>">
    <?php _e("Exclude posts (id's)", 'list-category-posts')?>
  </label>
  <br/>
  <input id="<?php echo $this->get_field_id('excludeposts'); ?>"
    name="<?php echo $this->get_field_name('excludeposts'); ?>" type="text"
    value="<?php echo esc_attr($excludeposts); ?>" />
</p>

<p>
  <label><?php _e("Show", 'list-category-posts')?>: </label><br/>
  <input type="checkbox" <?php checked( (bool) $instance['thumbnail'], true ); ?>
    name="<?php echo $this->get_field_name( 'thumbnail'); ?>" /> <?php _e("Thumbnail - size", 'list-category-posts')?>
    <select id="<?php echo $this->get_field_id('thumbnail_size'); ?>"
      name="<?php echo $this->get_field_name( 'thumbnail_size' ); ?>" type="text">
      <option value='thumbnail'>thumbnail</option>
      <option value='medium'>medium</option>
      <option value='large'>large</option>
      <option value='full'>full</option>
    </select>
</p>

<p>
  <input class="checkbox"  type="checkbox"
    <?php checked( (bool) $instance['show_date'], true ); ?>
    name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
  <?php _e("Date", 'list-category-posts')?>
</p>
<p>
  <input class="checkbox" input type="checkbox"
    <?php checked( (bool) $instance['show_author'], true ); ?>
    name="<?php echo $this->get_field_name( 'show_author' ); ?>" />
  <?php _e("Author", 'list-category-posts')?>
</p>
<p>
  <input class="checkbox" input type="checkbox"
    <?php checked( (bool) $instance['show_catlink'], true ); ?>
    name="<?php echo $this->get_field_name( 'show_catlink' ); ?>" />
  <?php _e("Link to category", 'list-category-posts')?>
</p>
<p>
  <input class="checkbox" input type="checkbox"
    <?php checked( (bool) $instance['show_excerpt'], true ); ?>
      name="<?php echo $this->get_field_name( 'show_excerpt' ); ?>" />
  <?php _e("Excerpt", 'list-category-posts')?>
</p>
<p>
  <label for="<?php echo $this->get_field_id('excerpt_size'); ?>">
    <?php _e("Excerpt size", 'list-category-posts')?>:
  </label>
  <br/>
  <input class="widefat" id="<?php echo $this->get_field_id('excerpt_size'); ?>"
    name="<?php echo $this->get_field_name('excerpt_size'); ?>" type="text"
    value="<?php echo esc_attr($excerptsize); ?>" />
</p>
<p>
  <label for="<?php echo $this->get_field_id('morelink'); ?>">
    <?php _e("More link", 'list-category-posts')?>:
  </label>
  <br/>
  <input class="widefat" id="<?php echo $this->get_field_id('morelink'); ?>"
    name="<?php echo $this->get_field_name('morelink'); ?>" type="text"
    value="<?php echo esc_attr($morelink); ?>" />
</p>