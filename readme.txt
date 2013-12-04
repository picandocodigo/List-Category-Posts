=== List category posts ===
Contributors: fernandobt
Donate Link: http://picandocodigo.net/programacion/wordpress/list-category-posts-wordpress-plugin-english/#support
Tags: list, categories, posts, cms
Requires at least: 3.3
Tested up to: 3.7.1
Stable tag: 0.38
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==
List Category Posts allows you to list posts from a category into a post/page using the [catlist] shortcode. When you're editing a page or post, directly insert the shortcode in your text and the posts will be listed there. The **basic** usage would be something like this:

`[catlist id=1]`

`[catlist name="news"]`

The shortcode accepts a category name or id, the order in which you want the posts to display, and the number of posts to display. You can also display the post author, date, excerpt, custom field values, even the content! The [catlist] shortcode can be used as many times as needed with different arguments on each post/page. You can add a lot more parameters according to what and how you want to show your post's list:
`[catlist id=1 numberposts=10]`

**Please read [the instructions](http://wordpress.org/extend/plugins/list-category-posts/other_notes/)** to learn what parameters are available and how to use them.

**Customization**: The different elements to display con be styled with CSS. you can define an HTML tag to wrap the element with, and a CSS class for this tag. Check [Other Notes](http://wordpress.org/extend/plugins/list-category-posts/other_notes/) for usage.

Great to use WordPress as a CMS, and create pages with several categories posts.

**Widget**: It includes a widget which works pretty much the same as the plugin. Just add as many widgets as you want, and select all the available options from the Appearence > Widgets page.

Please, read the information on [Other Notes](http://wordpress.org/extend/plugins/list-category-posts/other_notes/) and [Changelog](http://wordpress.org/extend/plugins/list-category-posts/changelog/) to be aware of new functionality, and improvements to the plugin.

**Videos**

Some users have made videos on how to use the plugin, (thank you! you people are awesome!). Check them out here:

 * [Manage WordPress Content with List Category Posts Plugin](http://www.youtube.com/watch?v=kBy_qoGKpdo)
 * [Build A Start Here Page with List Category Posts](http://www.youtube.com/watch?v=9YJpZfHIwIY)
 * [WordPress: How to List Category Posts on a Page](http://www.youtube.com/watch?v=Zfnzk4IWPNA)

**Support the plugin**

If you've found the plugin useful, consider making a [donation via PayPal](http://picandocodigo.net/programacion/wordpress/list-category-posts-wordpress-plugin-english/ "Donate via PayPal") or visit my Amazon Wishlist for [books](http://www.amazon.com/gp/registry/wishlist/2HU1JYOF7DX5Q/ref=wl_web "Amazon Wishlist") or [comic books](http://www.amazon.com/registry/wishlist/1LVYAOJAZQOI0/ref=cm_wl_rlist_go_o) :).

**Development**

I've moved the development to [GitHub](https://github.com/picandocodigo/List-Category-Posts). Fork it, code, make a pull request, suggest improvements, etc. over there. I dream of the day all of the WordPress plugins will be hosted on Github :)


==Installation==

* Upload listcat directory into your wp-content/plugins/ directory.
* Login to your WordPress Admin menu, go to Plugins, and activate it.
* You can find the List Category Posts widget in the Appearence > Widgets section on your WordPress Dashboard.
* If you want to customize the way the plugin displays the information, check the section on Templates on this documentation.

==Other notes==

==INSTRUCTIONS on how to use the plugin==

==Selecting the category==
The plugin can figure out the category from which you want to list posts in several ways. **You should use only one of these methods** since these are all mutually exclusive, weird results are expected when using more than one:

* Using the *category id*.
  * **id** - To display posts from a category using the category's id. Ex: `[catlist id=24]`.
* The *category name or slug*.
  * **name** - To display posts from a category using the category's name or slug. Ex: `[catlist name=mycategory]`
* *Detecting the current post's category*. You can use the *categorypage* parameter to make it detect the category id of the current posts, and list posts from that category.
  * **categorypage** - Set it to "yes" if you want to list the posts from the current post's category. `[catlist categorypage="yes"]`

When using List Category Posts whithout a category id, name or slug, it will post the latest posts from every category.

==Using several categories==

* **include** posts from several categories with **AND** relationship, posts that belong to all of the listed categories (note this does not show posts from any children of these categories): `[catlist id=17+25+2]` - `[catlist name=sega+nintendo]`.
* **include** posts from several categories with **OR** relationship, posts that belong to any of the listed categories: `[catlist id=17,24,32]` - `[catlist name=sega,nintendo]`.
* **exclude** a category with the minus sign (-): `[catlist id=11,-32,16]`, `[catlist id=1+2-3]`. **Important**: When using the *and* relationship, the order must be categories you want to include first, and exclude after. So `[catlist id=1+2-3]` will work, but `[catlist id=1+2-3+4]` won't.

==Pagination==

To use pagination, you need to set the following parameters:

* **pagination** set it to yes.

* **numberposts** - Posts per page are set with the `numberposts` parameter.

* **instance** (only necessary when using the shortcode with
    pagination more than once in the same page/post) - a number or
    name to identify the instance where you are using pagination.
    Since you can use the shortcode several times in the same page or
    post, you need to identify the instance so that you paginate only
    that instance.


Example:

`[catlist id=3 numberposts=5 pagination=yes instance=1]`

`[catlist id=5 numberposts=15 pagination=yes instance=2]`

==Changing the pagination CSS==

If you want to customize the way the pagination is displayed, you can
copy the `lcp_paginator.css` file from the plugin's directory to your
theme's directory and customize it. Do not customize the file on the
plugin's directory since this file will be overwritten every time you
update the plugin.

==Other parameters==

* **tags** - Tag support, you can display posts from a certain tag.

* **exclude_tags** - Exclude posts from one or more tags: `[catlist tags="videogames" exclude_tags="sega,sony"]`

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
  * **type** - Sort by type. Ex: `[catlist name=mycategory orderby=date]`

* **customfield_orderby** - You can order the posts by a custom field. For example: `[catlist numberposts=-1 customfield_orderby=Mood order=desc]` will list all the posts with a "Mood" custom field. Remember the default order is descending, more on order:

* **order** - How to sort **orderby**. Valid values are:
  * **ASC** - Ascending (lowest to highest).
  * **DESC** - Descending (highest to lowest). Ex: `[catlist name=mycategory orderby=title order=asc]`

* **numberposts** - Number of posts to return. Set to 0 to use the max number of posts per page. Set to -1 to remove the limit. Default: 5. Ex: `[catlist name=mycategory numberposts=10]`

* **monthnum** and **year** - List posts from a certain year or month. You can use these together or independently. Example: `[catlist year=2015]` will list posts from the year 2015. `[catlist monthnum=8]` will list posts published in August of every year. `[catlist year=2012 monthnum=12]` will list posts from December 2012.

* **search** - List posts that match a search term. `[catlist search="The Cake is a lie"]`

* **date** - Display post's date next to the title. Default is 'no', use date=yes to activate it.

* **author** - Display the post's author next to the title. Default is 'no', use author=yes to activate it.

* **dateformat** - Format of the date output. The default format is the one you've set on your WordPress settings. Example: `[catlist id=42 dateformat="l F dS, Y"]` would display the date as "Monday January 21st, 2013". Check http://codex.wordpress.org/Formatting_Date_and_Time for more options to display date.

* **excerpt** - Display the post's excerpt. Default is 'no', use `excerpt=yes` to activate it. If you don't have an excerpt in your post, the plugin will fetch this text from the content, striping its images, shortcodes and HTML tags. The limit is set by the *excerpt_size* parameter (55 words by default). If you want the automatically generated excerpt to respect your theme's allowed HTML tags, you should use `excerpt_strip=no`. If the post has an excerpt, the HTML tags are automatically stripped. If you want to overwrite the post's excerpt with an automatically generated one (may be usefull to allow HTML tags), use `excerpt_overwrite=yes`. I added this last parameter to have consistency across excerpts.

* **excerpt_size** - Set the number of *words* to display from the excerpt. Default is 55. Eg: `excerpt_size=30`

* **excerpt_strip** - Set it to `yes` to strip the excerpt's HTML tags.

* **excludeposts** - IDs of posts to exclude from the list. Use 'this' to exclude the current post. Ex: `[catlist excludeposts=this,12,52,37]`

* **offset** - You can displace or pass over one or more initial posts which would normally be collected by your query through the use of the offset parameter.

* **content** - Show the full content of the post. If there's a &lt;!--more--&gt; tag in the post, then it will behave just as WordPress does: only show the content previous to the more tag. Default is 'no'. Ex: `[catlist content=yes]`

* **catlink** - Show the title of the category with a link to the category. Use the **catlink_string** option to change the link text. Default is 'no'. Ex: `[catlist catlink=yes]`. The way it's programmed, it should only display the title for the first category you chose, and include the posts from all of the categories. I thought of this parameter mostly for using several shortcodes on one page or post, so that each group of posts would have the title of that group's category. If you need to display several titles with posts, you should use one [catlist] shortcode for each category you want to display.

* **category_count** -  Shows the posts count in that category, only works when using the **catlink** option: `[catlist name=nintendo catlink=yes category_count=yes]`

* **comments** - Show comments count for each post. Default is 'no'. Ex: `[catlist comments=yes]`.

* **thumbnail** - Show post thumbnail (http://markjaquith.wordpress.com/2009/12/23/new-in-wordpress-2-9-post-thumbnail-images/). Default is 'no'. Ex: `[catlist thumbnail=yes]`.

* **thumbnail_size** - Either a string keyword (thumbnail, medium, large or full) or 2 values representing width and height in pixels. Ex: `[catlist thumbnail_size=32,32]` or `[catlist thumbnail_size=thumbnail]`

* **thumbnail_class** - Set a CSS class for the thumbnail.

* **post_type** - The type of post to show. Available options are: post - Default, page, attachment, any - all post types.

* **post_status** - use post status, default value is 'publish'. Valid values:
  * **publish** - a published post or page.
  * **pending** - post is pending review.
  * **draft** - a post in draft status.
  * **auto-draft** - a newly created post, with no content.
  * **future** - a post to publish in the future.
  * **private** - not visible to users who are not logged in.
  * **inherit** - a revision. see get_children.
  * **trash** - post is in trashbin (available with Version 2.9).
  * **any** - retrieves any status except those from post types with 'exclude_from_search' set to true.


* **post_parent** - Show only the children of the post with this ID. Default: None.

* **class** - CSS class for the default UL generated by the plugin.

* **custom fields** - To use custom fields, you must specify two values: customfield_name and customfield_value. Using this only show posts that contain a custom field with this name and value. Both parameters must be defined, or neither will work.

* **customfield_display** - Display custom field(s). You can specify many fields to show, separating them with a coma.

* **template** - File name of template from templates directory without extension. Example: For 'template.php' value is only 'template'. Default is 'default', which displays an unordered list (ul html tag) with a CSS class. This class can be passed as a parameter or by default it's: 'lcp_catlist'. You can also use the default 'div' value. This will output a div with the 'lcp_catlist' CSS class (or one you pass as parameter with the class argument). The inner items (posts) will be displayed between p tags.

* **morelink** - Include a "more" link to access the category archive for the category. The link is inserted after listing the posts. It receives a string of characters as a parameter which will be used as the text of the link. Example: `[catlist id=38 morelink="Read more"]`

* **posts_morelink** - Include a "read more" link after each post. It receives a string of characters as a parameter which will be used as the text of the link. Example: `[catlist id=38 posts_morelink="Read more about this post"]`

* **link_target** - Select the `target` attribute for links to posts (target=_blank, _self, _parent, _top, *framename*). Example: `[catlink id=3 link_target=_blank]` will create: `<a href="http://localhost/wordpress/?p=45" title="Test post" target="_blank">Test post</a>`

== HTML & CSS Customization ==

You can customize what HTML tags different elements will be sorrounded with and a CSS class for this element, or just a CSS class which will wrap the element with a `span` tag.

The customizable elements (so far) are: author, catlink (category link), comments, date, excerpt, morelink ("Read More" link), thumbnail and title (post title).

The parameters are:
`autor_tag, author_class, catlink_tag, catlink_class, comments_tag, comments_class, date_tag, date_class,
excerpt_tag, excerpt_class, morelink_class, thumbnail_class, title_tag, title_class, posts_morelink_class`

So for example, let's say you want to wrap the displayed comments count with the p tag and a "lcp_comments" class, you would do:
`[catlist id=7 comments=yes comments_tag=p comments_class=lcp_comments]`
This would produce the following code:
`<p class="lcp_comments"> (3)</p>`

Or you just want to style the displayed date, you could wrap it with a span tag:
`[catlist name=blog date=yes date_tag=span date_class=lcp_date]`
This would produce the following code:
`<span class="lcp_date">March 21, 2011</span>`

Elements without a specified tag, but a specified class, will be wrapped with a span tag and its class. For example this:
`[catlist id=7  date=yes date_class="lcp_date"]`
Will produce the following:
`<span class="lcp_date">October 23, 2013</span>`


== Template System ==

Templates for the List Category Plugin are searched for in your WordPress theme's folder. You should create a folder named list-category-posts under 'wp-content/themes/your-theme-folder'. Template files are .php files.

You can use the included template as an example to start. It's in the plugin's template folder under the name default.php. To use a template, use this code:
`[catlist id=1 template=templatename]`
If the template file were templatename.php.

You can have as many different templates as you want, and use them in different pages and posts. The template code is pretty well documented, so if you're a bit familiar with HTML and PHP, you'll have no problems creating your own template. I'm planning on reworking the template system in order to have a really user friendly way to create templates.

== Frequently Asked Questions ==
* **Instructions** on how to use the plugin: http://wordpress.org/extend/plugins/list-category-posts/other_notes/ - **Read it**.
* **Template system** how to customize the way the posts are shown: http://wordpress.org/extend/plugins/list-category-posts/other_notes/. I am aware the Template System is not really friendly right now, I'll work on this whenever I get the time to work on the plugin for a while.
* **New feature requests, Bug fixes, enhancements** - You can post them on [GitHub Issues](https://github.com/picandocodigo/List-Category-Posts/issues).
* **Questions** For questions either use the [Support forum](http://wordpress.org/support/plugin/list-category-posts) or [WordPress Answers](http://wordpress.stackexchange.com/). It's a great place with a large community of WordPress users and developers. Just [ask your question](http://wordpress.stackexchange.com/questions/ask?tags=plugin-list-category-posts) using the 'plugin-list-category-post' tag.


* **FAQ**

**Does not work on servers with PHP < 5**

This is true since version 0.18. If you're still using PHP 4 on your webhost, you should consider upgrading to PHP 5. WordPress 3.1 was the last version to support PHP 4, from 3.2 and forward, only PHP 5 is supported. You can still [download an older version of the plugin](https://wordpress.org/extend/plugins/list-category-posts/download/ "download an older version of the plugin") if you're using PHP 4.

**Plugin could not be activated because it triggered a fatal error.**

*Parse error: syntax error, unexpected T_STRING, expecting T_OLD_FUNCTION or T_FUNCTION or T_VAR or '}' in /.../wp-content/plugins/list-category-posts/include/CatListDisplayer.php on line 10*

Please check:
http://wordpress.stackexchange.com/questions/9338/list-category-posts-plugin-upgrade-fails-fatal-error/9340#9340

**How do I display the Thumbnail next to the title?**

To see the thumbnail next to the title, you can add a class to it like
this:

`[catlist id=1 thumbnail=yes thumbnail_class=lcp_thumbnail]`

Then in your theme's stylesheet add this code:

`.lcp_thumbnail{
  float: left;
}

.lcp_catlist li{
  clear: both;
}`

If you want the thumbnail to the right, just change the `float: left`
attribute to `float: right`.


**How to not display the title**

You have to add a CSS class to the title with the `title_class` parameter. Then edit the title_class class in your theme's CSS file. Something like this:

In the post:
`[catlist id=3 title_class=lcp_title numberposts=1]`

And in your theme's CSS:

`.lcp_title{
  display: none;
}`

**How to insert the shortcode on the theme and not a post or page**

`<?php echo do_shortcode("[catlist id=3]"); ?>`

**Please do not ask for support when you are having issues with your CSS**. I can't solve every user's CSS problems. Feel free to ask on the forums or WP Answers. But please, **[read the instructions first](http://wordpress.org/extend/plugins/list-category-posts/other_notes/)**.

== Upgrade Notice ==

= 0.37 =

When using `content=yes`, if the post has a more tag, the plugin will only show the content previous to the more tag and not all the content as it used before (it now supports the more tag the same way as WordPress).

= 0.34 =
 * Now the plugin accepts either class or tag or both for styling elements (such as date, author, etc. to display). When just using a tag, it will sorround the element with that tag. When using just a class, it will sorround the element between span tags and the given CSS class. Check [Other notes](http://wordpress.org/extend/plugins/list-category-posts/other_notes/) under **HTML & CSS Customization** for more info.
 * Fixed bug on `post_status`, it used to show all published posts and if user was logged in, all private ones too. Now you can specify 'private' to just display private posts, and draft, publish, draft, etc (See **post_status** param on the [instructions](http://wordpress.org/extend/plugins/list-category-posts/other_notes/) for more info).

= 0.25 =
* Translation support.

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

= 0.38 =

 * Adds pagination. Check **Pagination** on [Other
   notes](http://wordpress.org/extend/plugins/list-category-posts/other_notes/)
   to learn how to use it.
 * Adds "How to display thumbnails next to title" to the FAQ.
 * Adds a Vagrant box for developers to be able to start coding with
   no effort :)

= 0.37 =
 * Supports `more` tag.  If there's a &lt;!--more--&gt; tag in the post, then it will behave just as WordPress does: only show the content previous to the more tag.
 * Fixes YouTube thumbnails: Includes "embed" urls for youtube video
   thumbnails, makes correct img tag when using CSS class.

= 0.36.2 =

 * Fixed category_count for several categories.

= 0.36.1 =

 * Fixed catlink to display titles for all the categories when using more than one category.

= 0.36 =

 * Adds option for "target=_blank" for post links.
 * Adds option to exclude category when using the *and* relationship: `[catlist id=1+2-3]` will include posts from categories 1 and 2 but not 3.

= 0.35 =
 * Updated Turkish translation, thanks again [Hakan Er](http://hakanertr.wordpress.com/)!
 * Adds feature to order by custom field using the `customfield_orderby` parameter.

= 0.34.1 =
 * Bugfix (removed var_dump)

= 0.34 =
 * Now accepts either class or tag or both for styling elements (such as date, author, etc. to display). When just using a tag, it will sorround the element with that tag. When using just a class, it will wrap the element between span tags and the given CSS class. Check [Other notes](http://wordpress.org/extend/plugins/list-category-posts/other_notes/) under **HTML & CSS Customization** for more info.
 * Fixed bug on `post_status`, it used to show all published posts and if user was logged in, all private ones too. Now you can specify 'private' to just display private posts, and draft, publish, draft, etc (See **post_status** param on the [instructions](http://wordpress.org/extend/plugins/list-category-posts/other_notes/) for more info).

= 0.33 =
 * Fixes bug with thumbnail size on Widget.
 * Adds feature to make widget title a link to the category. Use 'catlink' as the value for the widget's title to make it a link to the category (based on https://github.com/picandocodigo/List-Category-Posts/pull/51/).
 * Fixes morelink styiling with CSS class and tag.
 * Adds morelink to templates (based on https://github.com/picandocodigo/List-Category-Posts/pull/48/)
 * Fixes tag and CSS class for "catlink" too: http://wordpress.org/support/topic/cat_link-tag-does-not-seem-to-be-working

= 0.32 =
 * Add category count parameter to show the number of posts in a category next to its title. Only works when using the **catlink** option: `[catlist name=nintendo catlink=yes category_count=yes]` - http://wordpress.org/support/topic/count-feature

= 0.31 =
 * Pull request from @cfoellmann, adds testing environment and Travis CI integration. Awesomeness.
 * When searching for a thumbnail, if there's no thumbnail on the post but there's a YouTube video, display the YouTube video thumbnail. (wordpress.org/support/topic/youtube-thumbnail)

= 0.30.3 =
 * Bugfix release, fixes current category for post/page

= 0.30.2 =
 * Improves 'current category' detection.
 * Adds categorypage parameter to widget

= 0.30.1 =
 * **excerpt** - Fixed default excerpt behaviour from previous release. By default it **will** strip html tags as it always did. If you want it not to strip tags, you'll have to use `excerpt_strip=no`. Added a new parameter to have a consistent excerpt. If you want to overwrite WordPress' excerpt when using the plugin and generate one the way the plugin does when there's no excerpt, use `excerpt_overwrite=yes`.

= 0.30 =
 * Adds ability to exclude tags.
 * Changes excerpt. Since lot of users have asked for it, I once again modified the way the excerpt is shown. It now respects your theme's allowed HTML tags and doesn't strip them from the excerpt. If you want to strip tags, use `excerpt_strip=yes`.

= 0.29 =
 * Adds turkish translation, thanks [Hakan Er](http://hakanertr.wordpress.com/) for writing this translation! :)
 * Adds "AND" relationship to several categories. Thanks to [hvianna](http://wordpress.org/support/profile/hvianna) from the WordPress forums who [implemented this feature](http://wordpress.org/support/topic/list-only-posts-that-belong-to-two-or-more-categories-solution) :D
 * More improvements on readme.

= 0.28 =
 * Improvements on readme, faqs.
 * New posts_morelink param: adds a 'read more' link to each post.

= 0.27.1 =

 * Sets minimum version to WordPress 3.3, since wp_trim_words was introduced in that version. Adds workaround for people using WordPress < 3.3.
 * Adds Slovak translation by Branco from [WebHostingGeeks.com](http://webhostinggeeks.com/blog/).
 * Removes Debug PHP warnings.
 * Checkboxes on Widget save state, i18n for widget.
 * Adds excerpt size to widget.

= 0.27 =

 * Fixes to widget.
 * Adds year and month parameters to list posts from a certain year and/or month.
 * Adds search parameter to display posts that match a search term.

= 0.26 =

 * Adds i18n, German and Spanish translations. All credit to [cfoellmann](https://github.com/cfoellmann) for implementing this and writing the German translation. Thanks! :)

= 0.25.1 =

 * Changed excerpt limit, it uses word count, and is working for WordPress' excerpt and auto generated ones.

= 0.25 =

 * Better excerpt
 * Applies title filter, should work with qTranslate
 * Adds post status parameter
 * Adds meta links to plugin page - most importantly: INSTRUCTIONS (please read them).

= 0.24 =

 * Fixes "excerpt doesn't strip shortcodes" - https://github.com/picandocodigo/List-Category-Posts/issues/5
 * Exclude currently displayed post - [1](http://wordpress.stackexchange.com/questions/44895/exclude-current-page-from-list-of-pages/), [2](https://github.com/picandocodigo/List-Category-Posts/pull/8)
 * Add title to category title [1](http://wordpress.stackexchange.com/questions/44467/list-category-plugin-changing-the-links), will be improved.
 * Attempting to condition whitespaces to WordPress Coding Standard (emacs php-mode sucks for this...)
 * No more git-svn crap, now I'm developing this over at (GitHub)[https://github.com/picandocodigo/List-Category-Posts] and copying it into the WordPress SVN Repo.

= 0.23.2 =

 * Bugfix release

= 0.23.1 =

 * Bugfix release

= 0.23 =

This update is dedicated to [Michelle K McGinnis](http://friendlywebconsulting.com/) who bought me "Diamond Age" by Neal Stephenson from my [Amazon Wishlist](http://www.amazon.com/gp/registry/wishlist/2HU1JYOF7DX5Q/ref=wl_web). Thanks! :D

 * Added excerpt size. You can set how many characters you want the excerpt to display with 'excerpt_size'.
 * Fixed HTML tag and CSS class for each element (Check [Other notes](http://wordpress.org/extend/plugins/list-category-posts/other_notes/) for usage).
 * Removed shortcodes from excerpt.

= 0.22.3 =

 * Fixed thumbnail size parameter, added usage example on README.
 * Added space after author and date http://wordpress.org/support/topic/plugin-list-category-posts-space-required-after

= 0.22.2 =

 * Fixed bug with  the categorypage=yes param.
 * Tested with WordPress 3.3.

= 0.22.1 =

 * Fixed accidentally deleted line which made the catlink=yes param not work.

= 0.22 =

 * Added CSS "current" class hook for current post in the list: .current class attached to either the li or a tag of the currently viewed page in the said list. http://wordpress.stackexchange.com/q/35552/298
 * Added *morelink* parameter, check Other notes for usage.

= 0.21.2 =

 * Removed var_dump... (Sorry about that)

= 0.21.1 =

* Small fixes:
  * Used "empty()" function for some Strings instead of evaluating isset() and != ''.
  * Include parameters on the get_posts args only when they are set (post_parent among others).

= 0.21 =

* Added 'thumbnail_class' parameter, so you can set a CSS class to the thumbnail and style it.

= 0.20.5 =

* Brought back the multiple categories functionality for the id parameter. Hopefully the last 0.20 bugfix release so I can start working on new stuff to implement.
* Now the name parameter accepts multiple categories too. Just use: `[catlist name=category1,category2]`

= 0.20.4 =

* Yet another bugfix, regarding nothing being displayed when using tags.

= 0.20.3 =

* Fixed category detection code, which created some messy bugs in some cases

= 0.20.2 =

* Minor bugfix release

= 0.20.1 =

* Fixed extra " added to ul tag, thanks ideric (http://wordpress.org/support/topic/plugin-list-category-posts-extra-added-to-ul-tag)

= 0.20 =

* Added the possibility to list posts from the current post's category
* Some fixes to documentation

= 0.19.3 =

* Another taxonomy fix, thanks frisco! http://wordpress.org/support/topic/plugin-list-category-posts-problem-with-custom-taxonomies

= 0.19.2 =

* Small fix, missing parameter for taxonomy.

= 0.19.1 =

* Added thumbnail to Widget.
* Added thumbnail link to post (http://picod.net/33).

= 0.19 =

This update is dedicated to S. Keller from Switzerland who gave me "The Ultimate Hitchhiker's Guide to the Galaxy" from my Amazon Wishlit in appreciation for the plugin. I am really enjoying the read :D. If you, like S would like to show your appreciation, here's my [wishlist](http://www.amazon.com/gp/registry/wishlist/2HU1JYOF7DX5Q/ref=wl_web):

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
* Tested with WordPress 3.1 - http://wordpress.org/support/topic/399754


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
* Small fix -
added ul tags to default template.
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
