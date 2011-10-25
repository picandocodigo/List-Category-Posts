=== List category posts ===
Contributors: fernandobt
Donate Link: http://picandocodigo.net/programacion/wordpress/list-category-posts-wordpress-plugin-english/#support
Tags: list, categories, posts, cms
Requires at least: 2.8
Tested up to: 3.2.1
Stable tag: 0.19.2

== Description ==
List Category Posts allows you to list posts from a category into a post/page using the [catlist] shortcode.

The shortcode accepts a category name or id, the order in which you want the posts to display, and the number of posts to display. You can also display the post author, date, excerpt, custom field values, even the content! The [catlist] shortcode can be used as many times as needed with different arguments on each post/page.

Great to use WordPress as a CMS, and create pages with several categories posts.

The plugin includes a widget, which works pretty much the same as the plugin. Just add as many widgets as you want, and select all the available options from the Appearence > Widgets page.

Since version 0.18, **this plugins does not work on server with PHP 4**. If you're still using PHP 4 on your webhost, you should consider upgrading to PHP 5. WordPress 3.1 will be the last version to support PHP 4, from 3.2 and forward, only PHP 5 will be supported. You can still [download an older version of the plugin](https://wordpress.org/extend/plugins/list-category-posts/download/ "download an older version of the plugin") if you're using PHP 4.

Works correctly with WordPress 3.1 and default Twenty Ten theme
(http://wordpress.org/support/topic/399754)

**Usage**: `[catlist argument1=value1 argument2=value2]`

**Support the plugin**

If you've found the plugin useful, consider making a [donation via PayPal](http://picandocodigo.net/programacion/wordpress/list-category-posts-wordpress-plugin-english/ "Donate via PayPal") or visit my [Amazon Wishlist](http://www.amazon.com/gp/registry/wishlist/2HU1JYOF7DX5Q/ref=wl_web "Amazon Wishlist").


==Installation==

* Upload listcat directory into you wp-content/plugins/ directory.
* Login to your WordPress Admin menu, go to Plugins, and activate it.
* Edit the default.php file on templates to customize the way the categories are displayed, or use the default one included in the plugin's code. You can use several different templates if you want.
* You can find the ListCategoryPostsWidget in the Appearence > Widgets section on your WordPress Dashboard.

==Other notes==

**Usage**
The arguments you can use are:

* **name** - To display posts from a category using the category's name. Ex: [catlist name=mycategory]

* **id** - To display posts from a category using the category's id. Ex: [catlist id=24]. You can **include several categories**: Ex: [catlist id=17,24,32] or **exclude** a category with the minus (-)  
If you use both arguments (wrong!), List Category Posts will show the posts from the category in 'name'.

* **tags** - Tag support, you can display posts from a certain tag. 

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

* **dateformat** - Format of the date output. Default is get_option('date_format'). Check http://codex.wordpress.org/Formatting_Date_and_Time for possible formats.

* **template** - File name of template from templates directory without extension. Example: For 'template.php' value is only 'template'. Default is 'default', which displays an unordered list (ul html tag) with a CSS class. This class can be passed as a parameter or by default it's: 'lcp_catlist'. You can also use the default 'div' value. This will output a div with the 'lcp_catlist' CSS class (or one you pass as parameter with the class argument). The inner items (posts) will be displayed between p tags.

* **excerpt** - Display the post's excerpt. Default is 'no', use excerpt=yes to activate it.

* **excludeposts** - IDs of posts to exclude from the list. Ex: [catlist excludeposts=12,52,37]

* **offset** - You can displace or pass over one or more initial posts which would normally be collected by your query through the use of the offset parameter.

* **content** - Show the full content of the post. Default is 'no'. Ex: [catlist content=yes]

* **catlink** - Show the title of the category with a link to the category. Use the template system to customize its display using the variable $cat_link_string. Default is 'no'. Ex: [catlist catlink=yes]. The way it's programmed, it should only display the title for the first category you chose, and include the posts from all of the categories. I thought of this parameter mostly for using several shortcodes on one page or post, so that each group of posts would have the title of that group's category. If you need to display several titles with posts, you should use one [catlist] shortcode for each category you want to display.

* **comments** - Show comments count for each post. Default is 'no'. Ex: [catlist comments=yes].

* **thumbnail** - Show post thumbnail (http://markjaquith.wordpress.com/2009/12/23/new-in-wordpress-2-9-post-thumbnail-images/). Default is 'no'. Ex: [catlist thumbnail=yes].

* **thumbnail_size** - Either a string keyword (thumbnail, medium, large or full) or a 2-item array representing width and height in pixels, e.g. array(32,32).

* **post_type** - The type of post to show. Available options are: post - Default, page, attachment, any - all post types.

* **post_parent** - Show only the children of the post with this ID. Default: None.

* **class** - CSS class for the default UL generated by the plugin.

* **custom fields** - To use custom fields, you must specify two values: customfield_name and customfield_value. Using this only show posts that contain a custom field with this name and value. Both parameters must be defined, or neither will work.

* **customfield_display** - Display custom field(s). You can specify many fields to show, separating them with a coma. 


Your comments and feedback are welcome at:  
http://foro.picandocodigo.net/categories/list-category-posts

**New Code is welcome too** :D

== Frequently Asked Questions ==
* **Instructions** on how to use the plugin: http://foro.picandocodigo.net/discussion/251/list-category-posts-documentation/
* **Template system** how to customize the way the posts are shown: http://foro.picandocodigo.net/discussion/253/list-category-posts-using-templates/. I am aware the Template System is not really friendly right now, I'll work on this whenever I get the time to work on the plugin for a while.
* **New feature requests** - Contact me on fernando at picandocodigo dot net.
* **Support** I've decided to use WordPress Answers (http://meta.wordpress.stackexchange.com/) as the place for support. It's a great place with a large community of WordPress users and developers. Just ask your question with the tag 'plugin-list-category-post'.

* **FAQ**

Plugin could not be activated because it triggered a fatal error.
Parse error: syntax error, unexpected T_STRING, expecting T_OLD_FUNCTION or T_FUNCTION or T_VAR or '}' in /.../wp-content/plugins/list-category-posts/include/CatListDisplayer.php on line 10

Please check:
http://wordpress.stackexchange.com/questions/9338/list-category-posts-plugin-upgrade-fails-fatal-error/9340#9340

== Upgrade Notice ==

= 0.18 =
Template system was upgraded with new options. Backwards compatible, but you can better customize the way the post contents are displayed. Check templates/default.php.

= 0.17 =
Upgrade your templates: Templates system was rewritten, so your current templates will probably not work. Check out the new default.php file on /templates to see the simpler new way to work with templates.

= 0.13.2 =
Thumbnail parameter 'thumbnails' changed to 'thumbnail.

= 0.7.2 =
Template system has changed. Now the posts loop must be defined inside the template. Check templates/default.php for an example.

= 0.8 =
Widget built for WordPress 2.8's Widget API, so you need at least WP 2.8 to use the widget.

= 0.9 = 
Template system has changed. Custom templates should be stored in WordPress theme folder.

== Changelog ==

= 0.19.2 =
* Small fix, missing parameter for taxonomy.

= 0.19.1 =
* Added thumbnail to Widget.
* Added thumbnail link to post (http://picod.net/33).

= 0.19 =
This update is dedicated to S. Keller from Switzerland who gave me "The Ultimate Hitchhiker's Guide to the Galaxy" from my Amazon Wishlit in appreciation for the plugin. I am really enjoying the read :D. If you, like S would like to show your appreciation, here's my wishlist: http://www.amazon.com/gp/registry/wishlist/2HU1JYOF7DX5Q/ref=wl_web 
 
* Fixed private post logic, not displaying post if private. Thanks Bainternet from WordPress Answers: http://wordpress.stackexchange.com/questions/12514/list-category-posts-not-showing-posts-marked-private-to-logged-in-users/12520#12520
* Added thumbnail_size parameter.
* Added support for custom taxonomies and also moved to the array call of get_posts. Coded by wsherliker, thanks! http://picod.net/32
* Fixed widget, now it remembers saved options.

= 0.18.3 =
* Small excerpt fix, some readme file fixing too.
* Not showing the_content for password protected posts.

= 0.18.2 =
* Small fixes. Should work for name parameter in all cases now.

= 0.18.1 =
* Added slug and name to the fetching of category id from previous update.

= 0.18 =
* Fixed category id bug. Reported and fixed by Eric Celeste - http://eric.clst.org, thanks!
* Improved template system a liitle bit, now you can pass an HTML tag and a CSS class to sorround each field on your template.
* Added category link which wasn't working after previous big update.

= 0.17.1 =
* Fixed displaying of "Author:" even when not being called.

= 0.17 =
* Major rewrite. The whole code was rewritten using objects. It's easier now to develop for List Category Posts.
* Both STYLESHEETPATH and TEMPLATEPATH are checked for templates.

= 0.16.1 =
* Fixed shortcode nesting.

= 0.16 =
* Changed STYLESHEETPATH to TEMPLATEPATH to point to the parent theme.
* Added support to display custom fields. (http://picod.net/wp03)
* Tested with WordPress 3.1.

= 0.15.1 =
* Fixed a bug with undeclared variable. (Check http://picod.net/walcp, thanks Das!)

= 0.15 =
* Added custom fields support. Define both custom field (customfield_name) and value (customfield_value) to use it.

= 0.14.1 =
* Fixed "Show the title of the category with a link to the category" code (catlink param), it broke on some previous update, but now it's working again. Thanks Soccerwidow on the WP Forums for pointing this out. 

= 0.14 =
* Added "post_type" and "post_parent" from the underlining "get_posts()" API to be usable within the short-code. By Martin Crawford, thanks!
* Added the "class" parameter to style the default ul. You can pass a class name, or the plugin will use "lcp_catlist" bby default. Thanks Chocolaterebel (http://wordpress.org/support/topic/plugin-list-category-posts-sharing-my-own-template-in-lcp).
* Fixed "tags" parameter on the documentation, it used to say "tag", and the plugin looks for "tags".

= 0.13.2 =
* Fixed thumbnail code, added it to default.php template as example. 

= 0.13.1 =
* Fixed broken dateformat. 

= 0.13 =
* Show post thumbnails, should be tested, feedback on styling is welcome. Thanks to Sebastian from http://www.avantix.com.ar/

= 0.12 =
* Added comments count.
* Updated readme file

= 0.11.2 =
* Another minimal bug fixed with the excerpt...

= 0.11.1 =
* Fixed small bug which made the excerpt show up everytime... (Sorry :S)

= 0.11 =
* Automatic excerpt added in case the user didn't specifically write an excerpt.
* Widget has been finally fixed. The attributes finally save themselves, and the widget works as expected :D


= 0.10.1 =
* Small fix - added ul tags to default template.
* Compatible WordPress 3.0 with Twenty Ten theme (thanks again Doug Joseph :) )

= 0.10 =
* Code for the_content was fixed so that the content to output filtered content (thanks DougJoseph http://wordpress.org/support/topic/399754)

= 0.9 =
* admin parameter now shows "display name" instead of "user nice name".
* Template system has changed: In older version, custom templates got deleted if an automatic upgrade was done. Now templates are stored in the theme folder. (Thanks Paul Clark)
* Added tag support

= 0.8.1 =
* Fixed bug for 'content'.
* There's new stuff on the widget options. I'm still working on it, so some bugs may appear.

= 0.8 =
* Widget implements WP 2.8 Widget API, so at least 2.8 is required. Now you can use as many widgets as necessary, with new params.
* Updated readme file.

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
* Fixed small bug when using category name.

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
