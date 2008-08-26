=== List category posts ===
Contributors: fernandobt
Donate Link: http://picandocodigo.net/programacion/wordpress/list-category-posts-wordpress-plugin-english/
Tags: list, categories, posts, cms
Requires at least: 2.5
Tested up to: 2.6
Stable tag: 0.3

== Description ==
List Category Posts is a simple WordPress plugin which allows you to list posts from a category into a post/page using the [catlist] shortcode. This shortcode accepts a category name or id, the order in which you want the posts to display, and the number of posts to display. You can use [catlist] as many times as needed with different arguments. Great to use WordPress as a CMS, and create pages with several categories posts.
Usage: [catlist argument1=value1 argument2=value2].

==Installation==

* Upload listcat directory into you wp-content/plugins/ directory.
* Login to your WordPress Admin menu, go to Plugins, and activate it.
* Add “lcp_catlist” class into your theme’s CSS for custom formatting.
* You can find the List Category Posts widget in your widgets. Hasn't been tested, still in development, but usable.

**If you're updating List Category Posts from version 0.1**, you must change the code in the pages using it, since it's not backwards compatible. LCP now uses WordPress's shortcode API, in order to allow arguments. You should chang the previous [catlist=ID] to [catlist id=ID].

==Other notes==

**Usage**
The arguments you can use are:
'name' - To display posts from a category using the category's name. Ex: [catlist name=mycategory]
'id' - To display posts from a category using the category's id. Ex: [catlist id=24]
If you use both arguments (wrong!), List Category Posts will show the posts from the category in 'name'.

'orderby' - To customize the order. Valid values are: 
* 'author' - Sort by the numeric author IDs.
* 'category' - Sort by the numeric category IDs.
* 'content' - Sort by content.
* 'date' - Sort by creation date.
* 'ID' - Sort by numeric post ID.
* 'menu_order' - Sort by the menu order. Only useful with pages.
* 'mime_type' - Sort by MIME type. Only useful with attachments.
* 'modified' - Sort by last modified date.
* 'name' - Sort by stub.
* 'parent' - Sort by parent ID.
* 'password' - Sort by password.
* 'rand' - Randomly sort results.
* 'status' - Sort by status.
* 'title' - Sort by title.
* 'type' - Sort by type.
Ex: [catlist name=mycategory orderby=date]

'order' - How to sort 'orderby'. Valid values are:
* 'ASC' - Ascending (lowest to highest).
* 'DESC' - Descending (highest to lowest).
Ex: [catlist name=mycategory orderby=title order=asc]

'numberposts' - Number of posts to return. Set to 0 to use the max number of posts per page. Set to -1 to remove the limit. Default: 5 
Ex: [catlist name=mycategory numberposts=10]

You can customize the way List Category Posts shows the posts in your CSS by editing "lcp_catlist". The generated code is: <ul class="lcp_catlist">, and a <li> for each post in the category.

Since version 0.2, List Category Posts includes a sidebar widget. This is still in development, since I dedicated more time to the arguments in [catlist]. It does work, but you're welcome to test it and comment your feedback at: http://picandocodigo.net/programacion/wordpress/list-category-posts-wordpress-plugin-english/

List category posts was written with Geany - http://geany.uvena.de/

**Changelog**

0.3
* Broke backwards compatibility. Users of version 0.1 should update their pages and posts for the new shortcode formatting.
* Option to pass arguments to the plugin, in order to use name of category instead of ID, orderby, order and number of posts are passed through parameters.

0.2
* Added experimental sidebar widget (use at your own risk, not ready for prime-time yet since it hasn't been tested :P )

0.1.1
* Fixed major bug, which gave 404 error when trying to use "Options" page.

0.1
* Option page to limit number of posts.
* Working using [category=ID] for posts and pages, with several categories support.