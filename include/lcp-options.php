<?php
include_once("lcp-utils.php");
if ( is_admin() ){
  add_action( 'admin_menu', 'list_category_posts_menu' );
  add_action( 'admin_init', 'lcp_settings' );
}

function lcp_settings() { // whitelist options
  register_setting( 'list_category_posts_group', 'numberposts' );
  register_setting( 'list_category_posts_group', 'lcp_pagination' );
  register_setting( 'list_category_posts_group', 'lcp_orderby' );
  register_setting( 'list_category_posts_group', 'lcp_order' );
}

function list_category_posts_menu() {
    add_options_page( 'List Category Posts Options', 'List Category Posts',
                    'manage_options', 'list-category-posts',
                    'list_category_posts_options' );
}

function list_category_posts_options() {
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
?>
<div class="wrap">
  <h2>List Category Posts</h2>
  <p>
    <?php _e("These are general default options for List Category Posts. The idea in general will be that you can override them using the corresponding parameters in your shortcodes.","list-category-posts")?>
  </p>
  <form method="post" action="options.php">
    <?php
      settings_fields('list_category_posts_group');
      do_settings_sections('list_category_posts_group');
    ?>

    <table class="form-table">
      <tbody>
        <tr valign="top">
          <th scope="row">
            <label for="numberposts">
              <strong><?php _e("Number of Posts", "list-category-posts"); ?> :</strong>
            </label>
          </th>
          <td>
            <input type="text" id="numberposts" name="numberposts"  value="<?php echo esc_attr( get_option('numberposts') ); ?>"/>
            <small>
              <ul>
                <li>
                  <?php _e("Default number of posts (overriden using <code>numberposts</code> parameter on each shortcode).", "list-category-posts"); ?>
                </li>
                <li><?php _e("0 - displays the max number of posts per page", "list-category-posts");?></li>
                <li><?php _e("-1 - displays ALL THE POSTS (no limit)", "list-category-posts", "list-category-posts");?></li>
              </ul>
            </small>
          </td>
        </tr>

        <tr valign="top">
          <th scope="row">
            <label for="lcp_pagination">
              <strong><?php _e("Pagination", "list-category-posts"); ?> </strong>
            </label>
          </th>
          <td>
            <select name="lcp_pagination" id="lcp_pagination">
              <option value="false" <?php if(get_option('lcp_pagination') != 'true') echo 'selected="selected"' ?>>false</option>
              <option value="true" <?php if(get_option('lcp_pagination') === 'true') echo 'selected="selected"' ?>>true</option>
            </select>
          </td>
        </tr>

        <tr>
          <th scope="row">
            <label for="lcp_orderby">
              <strong><?php _e("Order by", "list-category-posts"); ?></strong>
            </label>
          </th>
          <td>
            <select id="lcp_orderby" name="lcp_orderby" type="text" >
              <?php
              $lcp_orders = LcpUtils::lcp_orders();
              $orderby = get_option('lcp_orderby');
              foreach ($lcp_orders as $key=>$value){
                $option = '<option value="' . $key . '" ';
                if ($orderby == $key){
                  $option .= ' selected = "selected" ';
                }
                $option .=  '>';
                echo $option;
                _e($value, 'list-category-posts');
                echo '</option>';
              }
              ?>
            </select>
          </td>
        </tr>

        <th scope="row">
          <label for="lcp_order">
            <strong><?php _e("Order", "list-category-posts"); ?></strong>
          </label>
        </th>
        <td>
          <select id="lcp_order" name="lcp_order" type="text">
            <?php $order = get_option('lcp_order'); ?>
            <option value='desc' <?php if($order == 'desc'): echo "selected: selected"; endif;?>>
              <?php _e("Descending", 'list-category-posts')?>
            </option>
            <option value='asc' <?php if($order == 'asc'): echo "selected: selected"; endif; ?>>
              <?php _e("Ascending", 'list-category-posts')?>
            </option>
          </select>
        </td>

      </tbody>
    </table>
    <?php submit_button(); ?>
  </form>
  <p>
    <em>
      <?php _e("Thanks for using List Category Posts.", "list-category-posts");?>
      <?php _e("If you need help with the plugin, please visit
      the <a href='http://wordpress.org/support/plugin/list-category-posts'>WordPress
      support forum</a>. Make sure
      you <a href='http://wordpress.org/extend/plugins/list-category-posts/other_notes/'>read
      the instructions</a> to be aware of all the things you can do
      with List Category Posts and <a href='https://github.com/picandocodigo/List-Category-Posts/blob/master/doc/FAQ.md#frequently-asked-questions'>check out the FAQ</a>.", "list-category-posts"); ?>
    </em>
  </p>

  <p>
    <em>
      <?php _e("Please post <strong>new feature requests, Bug fixes,
      enhancements</strong>
      to <a href='https://github.com/picandocodigo/List-Category-Posts/issues'>GitHub
      Issues</a> and check out the
      the <a href='https://github.com/picandocodigo/List-Category-Posts'>GitHub
      repo</a> if you want to contribute code.", "list-category-posts"); ?>
  </p>
  <p>
    <?php _e("If you've found the plugin useful, consider making
      a <a href='http://picandocodigo.net/programacion/wordpress/list-category-posts-wordpress-plugin-english/'
      title='Donate via PayPal' rel='nofollow'>donation via PayPal</a>
      or visit my Amazon Wishlist
      for <a href='http://www.amazon.com/gp/registry/wishlist/2HU1JYOF7DX5Q/ref=wl_web'
      title='Amazon Wishlist' rel='nofollow'>books</a>
      or <a href='http://www.amazon.com/registry/wishlist/1LVYAOJAZQOI0/ref=cm_wl_rlist_go_o'
      rel='nofollow'>comic books</a> :).", "list-category-posts"); ?>
    </em>
  </p>
</div>
<?php }
