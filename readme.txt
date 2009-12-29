=== List category posts ===
Contributors: fernandobt
Donate Link: http://picandocodigo.net/programacion/wordpress/list-category-posts-wordpress-plugin-english/
Tags: list, categories, posts, cms
Requires at least: 2.6
Tested up to: 2.9
Stable tag: 0.7.2

== Description ==
List Category Posts is a simple WordPress plugin which allows you to list posts from a category into a post/page using the [catlist] shortcode. This shortcode accepts a category name or id, the order in which you want the posts to display, and the number of posts to display. You can use [catlist] as many times as needed with different arguments. Great to use WordPress as a CMS, and create pages with several categories posts.
Usage: [catlist argument1=value1 argument2=value2].

==Installation==

* Upload listcat directory into you wp-content/plugins/ directory.
* Login to your WordPress Admin menu, go to Plugins, and activate it.
* Edit the default.php file on templates to customize the way the categories are displayed, or use the default one included in the plugin's code. You can use several different templates if you want.
* You can find the List Category Posts widget in your widgets. Hasn't been tested, still in development, but usable.

**If you're updating List Category Posts from version 0.1**, you must change the code in the pages using it, since it's not backwards compatible. LCP now uses WordPress's shortcode API, in order to allow arguments. You should chang the previous [catlist=ID] to [catlist id=ID].

==Other notes==

**Usage**
The arguments you can use are:

* **name** - To display posts from a category using the category's name. Ex: [catlist name=mycategory]

* **id** - To display posts from a category using the category's id. Ex: [catlist id=24]. You can **include several categories**: Ex: [catlist id=17,24,32] or **exclude** a category with the minus (-)  
If you use both arguments (wrong!), List Category Posts will show the posts from the category in 'name'.

* **orderby** - To customize the order. Valid values are: 
  * **author** - Sort by the numeric author IDs.
  * **category** - Sort by the numeric category IDs.
  * **content** - Sort by content.
  * **date** - Sort by creation date.
  * **ID** - Sort by numeric post ID.
  * **menu_order** - Sort by the menu order. Only useful with pages.
  * **mime_type** - Sort by MIME type. Only useful with attachments.
  * **modified** - Sort by last modified date.
  * **name** - Sort by stub.
  * **parent** - Sort by parent ID.
  * **password** - Sort by password.
  * **rand** - Randomly sort results.
  * **status** - Sort by status.
  * **title** - Sort by title.
  * **type** - Sort by type. Ex: [catlist name=mycategory orderby=date]
  * **order** - How to sort **orderby**. Valid values are:
  * **ASC** - Ascending (lowest to highest).
  * **DESC** - Descending (highest to lowest). Ex: [catlist name=mycategory orderby=title order=asc]

* **numberposts** - Number of posts to return. Set to 0 to use the max number of posts per page. Set to -1 to remove the limit. Default: 5. Ex: [catlist name=mycategory numberposts=10]

* **date** - Display post's date next to the title. Default is 'no', use date=yes to activate it.

* **author** - Display the post's author next to the title. Default is 'no', use author=yes to activate it.

* **dateformat** - Format of the date output. Default is get_option('date_format')

* **template** - File name of template from templates directory without extension. Example: For 'template.php' value is only 'template'. Default is 'default' that means template in code of plugin not in template file, that's an unordered list (ul html tag) with a CSS class: 'lcp_catlist'

* **excerpt** - Display the post's excerpt. Default is 'no', use excerpt=yes to activate it.

* **excludeposts** - IDs of posts to exclude from the list. Ex: [catlist excludeposts=12,52,37]

* **offset** - You can displace or pass over one or more initial posts which would normally be collected by your query through the use of the offset parameter.

* **content** - Show the full content of the post. Default is 'no'. Ex: [catlist content=yes]

* **catlink** - Show the link to the category. Use the template system to customize its display using the variable $cat_link_string. Default is 'no'. Ex: [catlist catlink=yes].

Since version 0.2, List Category Posts includes a sidebar widget. It works pretty much the same as the plugin itself.

Your comments and feedback are welcome at: http://picandocodigo.net/programacion/wordpress/list-category-posts-wordpress-plugin-english/

**New Code is welcome** :D

== Frequently Asked Questions ==
* **Instructions** on how to use the plugin: http://wordpress.org/extend/plugins/list-category-posts/other_notes/
* **Support forum** on the following URL: http://foro.picandocodigo.net/viewtopic.php?f=27&t=221
* **New feature requests** on the following URL: http://picandocodigo.net/programacion/wordpress/list-category-posts-wordpress-plugin-english/

== Upgrade Notice ==

= 0.7.2 =
Template system has changed. Now the posts loop must be defined inside the template.


== Changelog ==

= 0.7.2 =
* Fixed link to category.
* Improved template system.

= 0.7.1 =
* Fixed uber stupid bug with offset... Sorry about that!

= 0.7 =
* Exclude posts. Contribution by acub.
* Offset parameter on shortcode to start listing posts with an offset. Contribution by Levi Vasquez
* Content of the post can now be displayed. Contribution by Lang Zerner.
* Link to the category available. By request on the plugin's forum.

= 0.6 =
* Minor fix for unclosed ul if not using templates.
* Added option to list posts from many categories at once.
* Added option to exclude categories.

= 0.5 =
* Readme.txt validation.
* Added 'excerpt' parameter. You can now show the excerpt for each post.
* Added 'dateformat' parameter. Format of the date output. Default is get_option('date_format') - by Verex
* Added 'template' parameter. Now you can choose template for output of the plugin. File name of template from templates directory without extension. Example: For 'template.php' value is only 'template'. Default is 'default' that means template in code of plugin not in template file -by Verex

= 0.4.1 =

* Fixed some code to enable PHP 4 compatibility. Shouldn't hosting services update to PHP 5?

 = 0.4 =

* Added 'date' parameter. Now you can show the post's date when listed.
* Added 'author' parameter. You can also show the post's author.
* Sidebar Widget now allows you to add a title in h2 tags.
* Changed some variable names, to keep better compatibility with other plugins/wordpress variables.
* Tested with Wordpress 2.7.

 = 0.3 =

* Broke backwards compatibility. Users of version 0.1 should update their pages and posts for the new shortcode formatting.
* Option to pass arguments to the plugin, in order to use name of category instead of ID, orderby, order and number of posts are passed through parameters.

 = 0.2 =

* Added experimental sidebar widget (use at your own risk, not ready for prime-time yet since it hasn't been tested :P )

 = 0.1.1 =

* Fixed major bug, which gave 404 error when trying to use "Options" page.

 = 0.1 =

* Option page to limit number of posts.
* Working using [category=ID] for posts and pages, with several categories support.
