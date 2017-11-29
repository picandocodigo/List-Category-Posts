=== List category posts ===
Contributors: fernandobt
Donate Link: http://picandocodigo.net/programacion/wordpress/list-category-posts-wordpress-plugin-english/#support
Tags: list, categories, posts, cms
Requires at least: 3.3
Tested up to: 4.9
Requires PHP: 5.2.4
Stable tag: 0.74
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

List Category Posts allows you to list posts by category and many other parameters in a post, page or widget. You use the [catlist] shortcode to select which posts to show and how. There's tons of customizations available.

== Description ==

List Category Posts allows you to list posts by category in a post or page using the `[catlist]` shortcode. When you're editing a page or post, directly insert the shortcode in your text and the posts will be listed there. The *basic* usage would be something like this:

`[catlist id=1]`

`[catlist name="news"]`

The shortcode accepts a category name or id, the order in which you want the posts to display, and the number of posts to display. You can also display the post author, date, excerpt, custom field values, even the content! A lot of parameters have been added to customize what to display and how to show it. Check [the full documentation](https://github.com/picandocodigo/List-Category-Posts/wiki) to learn about the different ways to use it.

The `[catlist]` shortcode can be used as many times as needed with different arguments on each post/page.
`[catlist id=1 numberposts=10]`

There's an options page with only one option -for the moment-, new options will be implemented on demand (as long as they make sense). Right now the only global option is the `numberposts` parameter, to define a default number of posts to show for each instance (you can override this value by using the `numberposts` parameter in your shortcode).

**[Read the instructions](https://github.com/picandocodigo/List-Category-Posts/wiki)** to learn which parameters are available and how to use them.

If you want to **List Categories** instead of posts you can use my other plugin **[List categories](http://wordpress.org/plugins/list-categories/)**.

You can find **Frequently Asked Questions** [here](https://github.com/picandocodigo/List-Category-Posts/blob/master/doc/FAQ.md#frequently-asked-questions).

**Customization**

The different elements to display can be styled with CSS. you can define an HTML tag to wrap the element with, and a CSS class for this tag. Check [the documentation](https://github.com/picandocodigo/List-Category-Posts/wiki) for usage. You can also check [this nice tutorial](http://sundari-webdesign.com/wordpress-the-quest-to-my-perfect-list-view-for-posts-events-and-articles/) which gives lots of tips and how-to's to customize how to display the posts.

Great to use WordPress as a CMS, and create pages with several categories posts.

**Widget**

Since WordPress 4.9, [you can use shortcode in text widgets](https://make.wordpress.org/core/2017/10/24/widget-improvements-in-wordpress-4-9/). So you can just add a text widget in Appearence > Widgets and write the List Category Posts shortcode.

The plugin also includes a widget as a simple interface for its functionality. Just add as many widgets as you want, and select all the available options from the Appearence > Widgets page. Not all the functionality in the shortcode has been implemented in the widget yet. You can use the shortcode for the most flexibility.

Please, read the information on [the wiki](https://github.com/picandocodigo/List-Category-Posts/wiki) and [Changelog](http://wordpress.org/extend/plugins/list-category-posts/changelog/) to be aware of new functionality, and improvements to the plugin.

**Videos**

Some users have made videos on how to use the plugin (thank you, you are awesome!), check them out here:

 * [Manage WordPress Content with List Category Posts Plugin](http://www.youtube.com/watch?v=kBy_qoGKpdo)
 * [WordPress: How to List Category Posts on a Page](http://www.youtube.com/watch?v=Zfnzk4IWPNA)

**Support the plugin**

If you've found the plugin useful, consider making a [donation via PayPal](http://picandocodigo.net/programacion/wordpress/list-category-posts-wordpress-plugin-english/#support "Donate via PayPal") or visit my Amazon Wishlist for [books](http://www.amazon.com/gp/registry/wishlist/2HU1JYOF7DX5Q/ref=wl_web "Amazon Wishlist") or [comic books](http://www.amazon.com/registry/wishlist/1LVYAOJAZQOI0/ref=cm_wl_rlist_go_o) :).

**Development**

Development is being tracked on [GitHub](https://github.com/picandocodigo/List-Category-Posts). Fork it, code, make a pull request, suggest improvements, etc. over there. I dream of the day all of the WordPress plugins will be hosted on Git :)

Since the documentation on how to use the plugin has passed wordpress.org's character limit, the text was cut. I've since started using [a wiki](https://github.com/picandocodigo/List-Category-Posts/wiki) for more comfortable reading and maintaining. Please check it out, suggestions are welcome on GitHub issues!

==Instructions on how to use the plugin==

==SELECTING THE CATEGORY==
The plugin can figure out the category from which you want to list posts in several ways. **You should use only one of these methods** since these are all mutually exclusive, weird results are expected when using more than one:

* Using the *category id*.
  * **id** - To display posts from a category using the category's id. Ex: `[catlist id=24]`.
* The *category name or slug*.
  * **name** - To display posts from a category using the category's name or slug. Ex: `[catlist name=mycategory]`
* *Detecting the current post's category*. You can use the *categorypage* parameter to make it detect the category id of the current post, and list posts from that category.
  * **categorypage** - Set it to "yes" if you want to list the posts from the current post's category. `[catlist categorypage="yes"]`

When using List Category Posts whithout a category id, name or slug, it will post the latest posts from **every category**.

==USING MORE THAN ONE CATEGORY==

* Posts from several categories with an **AND** relationship, posts that belong to all of the listed categories (note this does not show posts from any children of these categories): `[catlist id=17+25+2]` - `[catlist name=sega+nintendo]`.
* Posts from several categories with an **OR** relationship, posts that belong to any of the listed categories: `[catlist id=17,24,32]` - `[catlist name=sega,nintendo]`.
* **Exclude** a category with the minus sign (-): `[catlist id=11,-32,16]`, `[catlist id=1+2-3]`. **Important**: When using the *and* relationship, you should write the categories you want to include first, and then the ones you want to exclude. So `[catlist id=1+2-3]` will work, but `[catlist id=1+2-3+4]` won't.

==Other ways of selecting what posts to show==

* **child_categories** - Exclude/include posts from the child categories. By default they are included. If you have a "Parent Category" and you use: `[catlist name="Parent Category"]`, you'll see posts from it's child categories as if they were posts from the same category. You can use this parameter to exclude these posts: `[catlist name="Parent Category" child_categories=false]`.

* **author_posts** - Get posts by author. Use 'user_nicename' (NOT
    name). Example: `[catlist author_posts="fernando"]`

* **tags** - Tag support, display posts from a certain tag. You can use an "OR" relationship `[catlist tags="nintendo,sega"]` or "AND" relationship (posts that belong to all of the listed tags): `[catilst tags="nintendo+sega"]`.

* **taxonomy** - You can select posts using custom taxonomies. There's two ways of selecting taxonomies:
    * You need to set the taxonomy and the terms: `[catlist taxonomy='person' terms='bob']`. It supports both the "IN" and "AND" logical relationships between each inner taxonomy. For the `AND` relationship, separate terms with a plus sign: ` [catlist taxonomy='topic' terms='topic1+topic2']`. For the `OR` relationship, separate terms with a comma: `[catlist taxonomy='topic' terms='topic1,topic2']`.
    * Multiple taxonomies: You can use multiple taxonomy terms in one shortcode like this:
      * OR - `taxonomies_or="tax1:{term1_1,term1_2};tax2:{term2_1,term2_2,term2_3}"`
      * AND - `taxonomies_and="tax1:{term1_1,term1_2};tax2:{term2_1,term2_2,term2_3}"`


* **currenttags** - Display posts from the current post's tags (won't
    work on pages since they have no tags). Pass it the 'yes' string for it to work: `[catlist currenttags="yes"]`

* **exclude_tags** - Exclude posts from one or more tags: `[catlist tags="videogames" exclude_tags="sega,sony"]`

* **starting_with** - Get posts whose title starts with a certain
    letter. Example: `[catlist starting_with="l"]` will list all posts
    whose title starts with L. You can use several letters: `[catlist starting_with="m,o,t"]`.

* **monthnum** and **year** - List posts from a certain year or month. You can use these together or independently. Example: `[catlist year=2015]` will list posts from the year 2015. `[catlist monthnum=8]` will list posts published in August of every year. `[catlist year=2012 monthnum=12]` will list posts from December 2012.

* **date ranges** - You can also use date ranges for listing posts. For example "list every post after March 14th, 2005". The parameters are: ```after, after_year, after_month, after_day, before, before_year, before_month,  before_day```. These parameters are used to specify data_query arguments (see: [the codex](https://codex.wordpress.org/Class_Reference/WP_Query#Date_Parameters)).  

     If you want to list all the posts before a given date, say `Jun 17th, 2007` you can use these two options:  
     `[catlist before_year=2007 before_month=06 before_day=17]`  
     Or you can use the `before` parameter with a [strtotime()-compatible string](http://php.net/manual/en/datetime.formats.date.php):  
     `[catlist before='2007/06/17']`  

     The same works for posts after a given date, you can use:  
     `[catlist after_year=2007 after_month=06 after_day=17]`  
     Or just `after` with a [strtotime()-compatible string](http://php.net/manual/en/datetime.formats.date.php):  
     `[catlist after='2007/06/17']`  

    `after` takes priority over `after_year`, `after_month`, and `after_day`.  
    `before` takes priority over `before_year`, `before_month`, and `before_day`.  

* **search** - List posts that match a search term. `[catlist search="The Cake is a lie"]`

* **excludeposts** - IDs of posts to exclude from the list. Use 'this' to exclude the current post. Ex: `[catlist excludeposts=this,12,52,37]`

* **offset** - You can displace or pass over one or more initial posts which would normally be collected by your query through the use of the offset parameter.

* **post_type** - The type of post to show. Available options are: post - Default, page, attachment, any - all post types. You can use several types, example: `[catlist post_type="page,post" numberposts=-1]`

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
  You can use several post statuses. Example: `[catlist post_status="future, publish" excludeposts=this]`

* **show_protected** - Show posts protected by password. By default
    they are not displayed. Use: `[catlist show_protected=yes]`

* **post_parent** - Show only the children of the post with this ID.
    Default: None.

* **custom fields** - To use custom fields, you must specify two values: customfield_name and customfield_value. `customfield_name` defines the name of the field, and you should choose the values for which you want posts to display. Using this only show posts that contain a custom field with this name and value. Both parameters must be defined, or neither will work. Eg: `[catlist customfield_name="color" customfield_value="green"]` will display posts with the value `green` set on the custom field `color`.

==PAGINATION

https://github.com/picandocodigo/List-Category-Posts/wiki/Pagination

==OTHER PARAMETERS==

* **conditional_title** - Display a custom title before the posts list.
    The title is not displayed if the list is empty. Set to the empty string
    (default value) to disable.
    Example: `[catlist conditional_title="Other posts"]`.

* **conditional_title_tag** - Specify the tag used for the conditional title.
    Defaults to 'h3'.

* **conditional_title_class** - Specify the class used for the conditional
    title. Defaults to the empty string (no special class).

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

* **numberposts** - Number of posts to return. Set to 0 to use the max
    number of posts per page. Set to -1 to remove the limit.
    Ex: `[catlist name=mycategory numberposts=10]`
    You can set the default number of posts globally on the options
    page on your Dashboard in Settings / List Category Posts.

* **no_posts_text** - Text to display when no posts are found. If you
    don't specify it, nothing will get displayed where the posts
    should be.

* **date** - Display post's date next to the title. Default is 'no',
    use date=yes to activate it. You can set a css class and an html
    tag to wrap the date in with `date_class` and `date_tag` (see HTML
    & CSS Customization further below).

* **date_modified** - Display the date a post was last modified next
    to the title. You can set a css class and an html tag to wrap the
    date in with `date_modified_class` and `date_modified_tag` (see
    HTML & CSS Customization further below).

* **author** - Display the post's author next to the title. Default is
    'no', use author=yes to activate it. You can set a css class and an html
    tag to wrap the author name in with `author_class` and `author_tag` (see HTML
    & CSS Customization further below).

    When displaying the post author, you can also display a link to the
    author's page. The following parameter **only works if author=yes
    is present in the shortcode**:

    * **author_posts_link** - Gets the URL of the author page for the
      author. The HTML and CSS customization are the ones applied to `author`.

* **dateformat** - Format of the date output. The default format is the one you've set on your WordPress settings. Example: `[catlist id=42 dateformat="l F dS, Y"]` would display the date as "Monday January 21st, 2013". Check http://codex.wordpress.org/Formatting_Date_and_Time for more options to display date.

* **excerpt** - Display a plain text excerpt of the post. Default is 'no', use `excerpt=yes` or `excerpt=full` to activate it. If you have a separate excerpt in your post, this text will be used. If you don't have an explicit excerpt in your post, the plugin will generate one from the content, striping its images, shortcodes and HTML tags. If you want to overwrite the post's separate excerpt with an automatically generated one (may be useful to allow HTML tags), use `excerpt_overwrite=yes`.

  If you use `excerpt=yes`, the separate excerpt or content will be limited to the number of words set by the *excerpt_size* parameter (55 words by default).

  If you use `excerpt=full` the plugin will act more like Wordpress. If the post has a separate excerpt, it will be used in full. Otherwise if the content has a &lt;!--more--&gt; tag then the excerpt will be the text before this tag, and if there is no &lt;!--more--&gt; tag then the result will be the same as `excerpt=yes`.

  If you want the automatically generated excerpt to respect your theme's allowed HTML tags, you should use `excerpt_strip=no`, otherwise the HTML tags are automatically stripped.

* **excerpt_size** - Set the number of *words* to display from the excerpt. Default is 55. Eg: `excerpt_size=30`

* **excerpt_strip** - Set it to `yes` to strip the excerpt's HTML tags. If the excerpt is auto generated by the plugin, the HTML tags will be stripped, and you should use `excerpt_strip=no` to see the excerpt with HTML formatting.

* **title_limit** - Set the limit of characters for the title. Ex:
    `[catlist id=2 title_limit=50]` will show only the first 50
    characters of the title and add "…" at the end.

* **content** - **WARNING**: If you want to show the content on your listed posts, you might want to do this from a new [Page Template](http://codex.wordpress.org/Page_Templates) or a [Custom Post Type](http://codex.wordpress.org/Post_Types#Custom_Post_Type_Templates) template. Using this parameter is discouraged, you can have memory issues as well as infinite loop situations when you're displaying a post that's using List Category Posts. You have been warned. Usage:

    * `yes` - Show the excerpt or full content of the post. If there's a &lt;!--more--&gt; tag in the post, then it will behave just as WordPress does: only show the content previous to the more tag. Default is 'no'. Ex: `[catlist content=yes]`

    * `full` - Show the full content of the post regardless of whether there is a &lt;!--more--&gt; tag in the post. Ex: `[catlist content=full]`

* **catlink** - Show the title of the category with a link to the category. Use the **catlink_string** option to change the link text. Default is 'no'. Ex: `[catlist catlink=yes]`. The way it's programmed, it should only display the title for the first category you chose, and include the posts from all of the categories. I thought of this parameter mostly for using several shortcodes on one page or post, so that each group of posts would have the title of that group's category. If you need to display several titles with posts, you should use one [catlist] shortcode for each category you want to display.

* **category_description** Show the category description wrapped in a p tag: `[catlist id=1 category_description='yes']`

* **catname** - Show the title of the category (or categories), works exactly as `catlink`, but it doesn't add a link to the category.

* **category_count** -  Shows the posts count in that category, only works when using the **catlink** option: `[catlist name=nintendo catlink=yes category_count=yes]`

* **comments** - Show comments count for each post. Default is 'no'. Ex: `[catlist comments=yes]`.

* **thumbnail** - Show post thumbnail (http://markjaquith.wordpress.com/2009/12/23/new-in-wordpress-2-9-post-thumbnail-images/). Default is 'no'. Ex: `[catlist thumbnail=yes]`.

* **force_thumbnail** - If the previous parameter is set to 'yes', and there's no featured image, setting this to 'yes' or 'true' will make the plugin look for the first image in the post and use it as a thumbnail. Ex: `[catlist thumbnail=yes force_thumbnail=yes]`.

* **thumbnail_size** - Either a string keyword (thumbnail, medium, large or full) or 2 values representing width and height in pixels. Ex: `[catlist thumbnail_size=32,32]` or `[catlist thumbnail_size=thumbnail]`

* **thumbnail_class** - Set a CSS class for the thumbnail.

* **post_suffix** - Pass a String to this parameter to display this
    String after every post title.
    Ex: `[catlist numberposts=-1
    post_suffix="Hello World"]` will create something like:

    ```<ul class="lcp_catlist" id=lcp_instance_0>
       <li>
         <a href="http://127.0.0.1:8080/wordpress/?p=42" title="WordPress">
           WordPress
         </a> Hello World </li>```

* **display_id** - Set it to yes to show the Post's ID next to the post title: `[catlist id=3 display_id=yes]`

* **class** - CSS class for the default UL generated by the plugin.

* **tags_as_class** - Use a post's tags as a class for the `li` that lists the posts. Default is `no`. For example, `[catlist tags_as_class=yes]` will show a post that has the `fun` tag like this:
```
<li class=" fun ">
  <a href="http://localhost:8080/?p=1267" title="Post Title">Post Title</a>
</li>
```

* **customfield_display** - Display custom field(s). You can specify
    many fields to show, separating them with a coma. If you want to
    display just the value and not the name of the custom field, use
    `customfield_display_name` and set it to no.
    By default, the custom fields will show inside a div with a
    specific class: `<div class="lcp-customfield">`. You can customize
    this using the customfield_tag and customfield_class parameters to
    set a different tag (instead of the div) and a specific class
    (instead of lcp-customfield).

* **customfield_display_glue** - Specify the text to appear between two custom
    fields if displayed together, defaults to the empty string. Not used if
    the `customfield_display_separately` parameter is defined.

* **customfield_display_separately** - Display the custom fields separately.
    Each custom field is displayd within its own tag (see `customfield_tag`).
    Defaults to 'no', set to 'yes' to enable. Superseeds the
    `customfield_display_glue` parameter when enabled.

* **customfield_display_name** - To use with `customfield_display`.
    Use it to just print the value of the Custom field and not the
    name. Example:
`[catlist numberposts=-1 customfield_display="Mood"
   customfield_display_name="no"]`
Will print the value of the Custom Field "Mood" but not the text
    "Mood: [value]".

* **customfield_display_name_glue** - To use with `customfield_display_name`.
    Use it to specify the text between the name and the value, defaults to
    ' : '.

* **template** - By default, posts will be listed in an unordered list
    (ul tag) with the class 'lcp_catlist':

    `<ul class="lcp_catlist"><li><a href="post1">Post 1</a></li>...`

    You can use a different class by using the *class* parameter.

    You can create your own template file (Check **Template System**
    further down this document) and pass it as a parameter here. The
    parameter is the template name without the extension. For example
    for `mytemplate.php`, the value would be `mytemplate`.

    You can also pass these two parameters which yield different
    results:
      * `div` - This will output a div with the `lcp_catlist` class
    (or one you pass as a parameter with the `class` argument). The
    posts will be displayed between p tags. `[catlist template=div]`

      * `ol` - This will output an ordered list with the `lcp_catlist`
      css class (or the one you pass as a parameter with the `class`
      argument) and each post will be a list item inside the ordered list. `[catlist template=ol]`.

* **morelink** - Include a "more" link to access the category archive for the category. The link is inserted after listing the posts. It receives a string of characters as a parameter which will be used as the text of the link. Example: `[catlist id=38 morelink="Read more"]`

* **posts_morelink** - Include a "read more" link after each post. It receives a string of characters as a parameter which will be used as the text of the link. Example: `[catlist id=38 posts_morelink="Read more about this post"]`

* **link_target** - Select the `target` attribute for links to posts (target=_blank, _self, _parent, _top, *framename*). Example: `[catlink id=3 link_target=_blank]` will create: `<a href="http://localhost/wordpress/?p=45" title="Test post" target="_blank">Test post</a>`

* **no_post_titles** - If set to `yes`, no post titles will be shown. This may make sense together with `content=yes`.

* **link_titles** - Option to display titles without links. If set to `false`, the post titles won't be linking to the article.

* **link_dates** - Option to wrap dates with a link to the post. Set to `true` or `yes` to enable, set to `false` or `no` to disable. Defaults to `false`.


==Installation==

* Upload the `list-category-posts` directory to your wp-content/plugins/ directory.
* Login to your WordPress Admin menu, go to Plugins, and activate it.
* Start using the '[catlist]` shortcode in your posts and/or pages.
* You can find the List Category Posts widget in the Appearence > Widgets section on your WordPress Dashboard.
* If you want to customize the way the plugin displays the information, check [HTML & CSS Customization](https://github.com/picandocodigo/List-Category-Posts/wiki/HTML-&-CSS-Customization) or the [section on Templates](https://github.com/picandocodigo/List-Category-Posts/wiki/Template-System) on the wiki.

== Frequently Asked Questions ==

You can find the Frequently Asked Questions [here](https://github.com/picandocodigo/List-Category-Posts/blob/master/doc/FAQ.md#frequently-asked-questions).

**INSTRUCTIONS ON HOW TO USE THE PLUGIN**

Check out [the Wiki](https://github.com/picandocodigo/List-Category-Posts/wiki/)

Please read the instructions and the FAQ before opening a new topic in the support forums.

**Widget**

The widget is quite simple, and it doesn't implement all of the plugin's functionality.

Since WordPress 4.9, you can use a shortcode in a widget. If you're using a previous WordPress version, add this code to your theme's functions.php file:

`add_filter('widget_text', 'do_shortcode');`

Then just add a new text widget to your blog and use the shortcode there as the widget's content.

**HTML & CSS Customization**

[HTML and CSS Customization](https://github.com/picandocodigo/List-Category-Posts/wiki/HTML-&-CSS-Customization)


**TEMPLATE SYSTEM**

How to customize the way the posts are shown: [Template System](https://github.com/picandocodigo/List-Category-Posts/wiki/Template-System). I am aware the Template System is not the friendliest right now, I'll work on improving this if I ever get the time to work on it.

**NEW FEATURE REQUESTS, BUG FIXES, ENHANCEMENTS**

You can post them on [GitHub Issues](https://github.com/picandocodigo/List-Category-Posts/issues).

**FURTHER QUESTIONS**

Please check the [FAQ](https://github.com/picandocodigo/List-Category-Posts/blob/master/doc/FAQ.md#frequently-asked-questions) before posting a question. You can post questions in the [Support forum](http://wordpress.org/support/plugin/list-category-posts) or [add a new issue on GitHub](https://github.com/picandocodigo/List-Category-Posts/issues).

== Upgrade Notice ==

= 0.66 =
Full release notes:
https://github.com/picandocodigo/List-Category-Posts/releases/tag/0.66

= 0.65 =
Full release notes here: https://github.com/picandocodigo/List-Category-Posts/releases/tag/0.65

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

= 0.74 =
* Adds "next" and "previous" classes to the previous and next buttons in pagination menu. The classes are `lcp_prevlink` and `lcp_nextlink`. Thanks @zymeth25.
* Adds pagination to Widget.

= 0.73.2 =

* Bug fix release: `[catlist categorypage="yes"]` had stopped working with WordPress 4.9. This is because the category check would compare a return value against the "Empty Term" translation key which was changed [in this commit](https://github.com/WordPress/WordPress/commit/a42b9cebdebb2809b484f4187757555f30c9ee08#diff-efd15818123d55a117706670be80c15aR749) adding periods at the end of some error messages. So it was just a matter of adding the period in the if condition. Thanks helmutka for reporting this!

= 0.73.1 =

* Bumped version because WordPress.org wouldn't grab the previous one.

= 0.73 =

* Support "and" relationship for custom taxonomies, by [Dalton Rooney](https://github.com/daltonrooney).
* Support for multiple taxonomies in the same shortcode by [TheSquiffy](https://github.com/TheSquiffy)
* Tested up to WordPress 4.9
* Adds Irish translation by Jordan Silaen.

= 0.72 =

Several bug fixes:

* Makes sure the `tags_as_class` instance variable is defined. This squelched an 'undefined index' PHP Notice that appeared for widgets that were last saved before upgrading to 0.71.1. Fix by Matthew Eppelsheimer (@MatthewEppelsheimer on GitHub).
* Adds a new tutorial in the docs, check it out!
* Fixes a bug where customfield_value wouldn't work if a custom field's value = 0
* Adds tag/class html customization refactor to excerpt to behave as expected:
    * If you provide `excerpt_tag` but not `excerpt_class`, excerpt will be wrapped with given tag.
    * If you provide `excerpt_tag` and `excerpt_class`, excerpt will be wrapped with provided tag and given class.
    * If you provide `excerpt_class` but not `excerpt_tag`, excerpt will be wrapped with a span and the given class.
* Fixes an error notice when widget is displayed for "current category" on post without category - `Notice: Undefined offset: 0 in /include/lcp-category.php on line 69`


= 0.71.1 =

* Fixes ["Undefined index: tags_as_class"](https://github.com/picandocodigo/List-Category-Posts/issues/227). Thanks @vacuus for the Pull Request! :)

= 0.71 =

* Added tags_as_class: Use a post's tags as a class for the li that lists the posts. Default is no. Thanks @vacuus on GitHub for this PR!

= 0.70 =

* Fixed [customfield_class and customfield_tag issues](https://github.com/picandocodigo/List-Category-Posts/issues/201). Thanks [vacuus](https://github.com/vacuus)!!
* Tested up to WordPress 4.6.1
* Added date range, thanks again [vacuus](https://github.com/vacuus)!! Check [the docs](https://github.com/picandocodigo/List-Category-Posts/wiki/How-to-select-which-posts-to-show) to read how to use this.

= 0.69 =

* Update lcp-widget.php for PHP 7 compatibility. Thanks @kenshin23!

= 0.68 =

Thanks @mmatthews1981, @ottadvantage and @mhoeher for their contributions on this version:

* Adds Alt Tag to thumbnail
* Handle child_categories flag correctly - https://github.com/picandocodigo/List-Category-Posts/pull/185
* Adds a default value to numberposts on plugin activation - https://github.com/picandocodigo/List-Category-Posts/pull/193


= 0.67.1 =
* Bugfix release, this should fix the issues with Parent Categories listings.

= 0.67 =
* Adds custom css class to current page in pagination `lcp_currentpage`.
* Adds child_categories parameter to being able to exclude child categories' posts from a list.
* New feature to look for the first image in a post when requesting a thumbnail and the post has no featured image. Thanks Michael J. Gibbs for writing this code :)

= 0.66 =
* Full release notes: https://github.com/picandocodigo/List-Category-Posts/releases/tag/0.66
* Orders the README a bit.
* Issues with tags when using more than one tag for OR and AND relationships should be fixed.
* Documented the use of custom taxonomies. For some reason I never came around to do that. I changed the parameters for taxonomies, it used the 'tags' parameter for 'terms' before, so I added a 'terms' parameter to make this independent from the tags parameter. So now it looks like this: `[catlist taxonomy='person' terms='bob']`. This might break some current uses of taxonomy, but since it was written so long ago and I don't know why it used "tags", I decided to just create the 'terms' parameter. People using the custom taxonomies were people who are looking at the code anyway since I can't find it documented anywhere. Sorry for the inconveniences!
* Adds category description parameter.
* Adds orderby and order to options page. Removes default values since they're the default anyway.

= 0.65 =

* Adds pagination parameter to the options page.
* Changes the loop in the default template.
* Fixes 'morelink_class not working with templates' in the default template.
* Adds link to post wrapper for the post date. If you have a chance, please thank [bibz](https://github.com/bibz) who is doing awesome Pull Requests to this plugin and occasionally helping out on the support forums here too :)

= 0.64 =

* Fixes get_current_tags
* Some updates on the documentation
* Introduces a conditional title, only displayed when posts are found, thanks [bibz](https://github.com/bibz) for this Pull Request!
* Introduces `customfield_display_separately`, `customfield_display_glue` and `customfield_display_name_glue` parameters for multiple custom fields handling by bibz. Thanks! :D

= 0.63.1 =
* Remove renamed file (Damn using subversion), should fix issues updating.

= 0.63 =

* Vagrant box and development environment improved by bibz
* Tested with WordPress 4.3, updated Widget constructor because of [PHP 4 deprecation](https://make.wordpress.org/core/2015/07/02/deprecating-php4-style-constructors-in-wordpress-4-3/).

= 0.62 =

* Dutch translation by Gerhard Hoogterp, thank you!
* Re-add the loop fixes and fixes function missing from last time by Sophist-UK, thanks!
* Allow to order by the modified date in the widget by bibz, thanks!

= 0.61 =

* Adds Portuguese from Portugal (pt_PT) translation, muito obrigado Joaquim Félix!
* Fixes translation paths, [thanks monpelaud](https://wordpress.org/support/topic/error-of-name-on-some-translation-files-1)!.


= 0.60.1 =

* Reverts switching to the loop til we find a way around for using templates.

= 0.60 =

* Fixes the loop so that other plugins work as if this was a blog or archive post.
See [issue #156](https://github.com/picandocodigo/List-Category-Posts/issues/156)
on Github. Thanks Sophist-UK for this new version :)

= 0.59.2 =

 * Tested with WordPress 4.2
 * Sophist's fix:  Check for multi-byte functions installed and use ascii functions if not.

= 0.59.1 =

* Fix some errors

= 0.59 =

**Thanks Sophist from UK for this release** :)

By Sophist:

* Fix error causing call to undefined method
* Add excerpt=full to allow either full explicit excerpt or use <?--more--> to define where the excerpt ends.
* Fixes link_titles=false creates plain text rather than unlinked formatted text as you might expect.
* Fixes title_limit not working correctly

Other minor fixes by me.

= 0.58.1 =
* Fixes an error with pagination links. Accessing $_SERVER filtered not working on some servers, have to investigate further for a future version.
* Addresses warning messages when debug enabled.

= 0.58 =
* Removes filter interfering with filters set by other plugins. Thanks [zulkamal](https://github.com/zulkamal) for the Pull Request!
* Adds option to display titles without links. Thanks zulkamal for this Pull Request too! :D
* Workaround to prevent '?&' to appear in URLs. Thanks [mhoeher](https://github.com/mhoeher) for the Pull Request!
* General refactors for improving code quality/security.
* Fixed typo in Readme (Thanks Irma!).
* Fixes excluding tags when using category name (should fix other issues with category name too since there was a bug there).

= 0.57 =
 * Add custom image sizes to the list of selectable image sizes in the widget. Thanks [nuss](https://github.com/nuss) for the Pull Request!
 * New Attribute 'no_post_titles'. Thanks [thomasWeise](https://github.com/thomasWeise) for the Pull Request!
 * Finnish localization. Thanks [Newman101](https://github.com/Newman101) for the Pull Request!

= 0.56 =
 * Adds Indonesian (Bahasa Indonesia) translation. Thanks Dhyayi Warapsari!
 * Adds french from France language. Thanks Dorian Herlory!
 * Adds content=full parameter to ignore <!--more--> tags when displaying content. Thanks Sophist-UK!
 * Fixes excluded_tags parameter

= 0.55 =
 * Ordered lists now follow the posts count when using pagination - https://wordpress.org/support/topic/templateol-resets-count-when-using-pagination
 * Fixes issue introduced in 0.54 with undefined indexes - https://wordpress.org/support/topic/problem-continues-with-0542

= 0.54.2 =
 * Fixes call to undefined method lcp_get_current_post_id()

= 0.54.1 =
 * Fixes bug in LcpParameters.

= 0.54 =
 * Adds http/https check for pagination links.
 * Fixes `post_status` and `post_type` parameters for using multiple post statuses and types.
 * Big refactor: Thumbnail code, parameters moved to new class,
 created util class, removed bad and repeated code, moved category
 code to new class.  Small fixes all around the place. Went from a
 very bad 1.77 GPA to 3.23 on CodeClimate.


= 0.53 =
 * Makes "starting_with" parameter accept several letters, by Diego Sorribas. Thank you!

= 0.52 =
 * Small fix for pagination and query string.
 * Fix on multiple categories with AND relationship.
 * Fixes options page 404 and saving options.
 * Tested with WordPress 4.1.

= 0.51 =
 * Fixes translations, updates Spanish translation. Translators, please update your po and mo files and submit them via pull request on GitHub :)
 * Test compatibility with WordPress 4.0
 * Adds icon for WordPress 4.0 new plugin interface.
 * Fixes posts_morelink and customfields for templates.
 * Adds fixes by [htrex](https://github.com/htrex):
   * Fix custom template regression
   * Fix excluded categories not working in widget

= 0.50.3 =

 * Addresses some warnings / scandir on Displayer and catname on widget
 * Fixes lcp_paginator.css path
 * Some small sanitations

= 0.50.2 =

 * Small fix on templates

= 0.50.1 =

 * Fixes issue with catlink.
 * Fixes issue with templates named "default"

= 0.50 =

 * Adds Thai translation by [itpcc](https://github.com/itpcc).
 * The widget can now select an existing template. Thanks [Borjan Tchakaloff](https://github.com/bibz)!
 * Templates code was refactored.

= 0.49.1 =

 * Makes sure "starting_with" queries are case insesitive.
 * Fixes category link on 'catlink' and 'catname' parameters (were showing twice)

= 0.49 =

* Adds `author_posts_link`, to show an author's page.
* Adds catname parameter to show just the category name (and not the link). Thanks user sirenAri from the [forum](http://wordpress.org/support/topic/a-couple-of-suggestions-and-one-teensy-error).
* Small bug fix for getting current category. Used to check against simple string, now checking against i18n'ed one.

= 0.48 =

 * Bug fixes
 * Adds parameter to show modified date of posts. Thanks Eric Sandine for the Pull Request :)

= 0.47 =

 * Adds Ukranian translation by Michael Yunat [http://getvoip.com](http://getvoip.com/blog)
 * Adds `display_id` parameter. Set it to yes to show the Post's ID next to the post title.
 * Adds `starting_with` parameter. Gets posts whose title start with a given letter.


= 0.46.4 =
 * Finally (hopefully) fix the excerpt issues.

= 0.46.3 =
 * Fix something that I broke on previous update for excerpt :S

= 0.46.2 =
 * Some fixes on displaying excerpt.

= 0.46.1 =
 * Fixes quotes bug on title tag.
 * Only show ellipsis when title.size > title_limit when using the
 title_limit param.

= 0.46 =
 * Adds "the_excerpt" filter to excerpt to improve compatibility with
 the [Jetpack](http://wordpress.org/plugins/jetpack/) plugin.
 * Add character limit to title
 * Removes debug warnings
 * Output valid HTML, attribute quotations - thanks Nikolaus Demmel!

= 0.45 =
 * Adds ol default template to `template` parameter.
 * Improves documentation.

= 0.44.1 =
 * Removes warning when using current tag in pages
 * Small fix on Readme

= 0.44 =
 * Adds the feature to get an author's posts
 * Adds show posts from current post's tags.

= 0.43.1 =
 * Show "no posts text" only if it's been set and there are no posts,
 otherwise behave like before.

= 0.43 =
 * Removes filters to order by (should fix issues with order)
 * Adds `pagination_prev` and `pagination_next` params to customize
 the "Previous" and "Next" buttons on pagination navigation.
 * Only show pages in pagination when they are > 1
 * Adds `no_posts_text` param to display a custom message when no
 posts are found
 * Fixes "morelink" class parameter (now can be used without
 specifying an HTML tag and the class is applied to the a tag).

= 0.42.3 =
  * Adds missing title attribute from thumbnail links.

= 0.42.2 =
  * Fixes pagination numbers
  * Removes warning on wp-debug set to true

= 0.42.1 =
 * Fixes some debug warnings (Ruby's nil doesn't apply in the PHP World)

= 0.42 =
 * Fixes excludeposts=this.
 * Adds customfield_tag and customfield_class to customize an HTML tag
 and CSS class for custom fields.

= 0.41.2 =
 * Small bugfix with customfield_display_name (wasn't working now it
 is)

= 0.41.1 =
 * Fixes customfield display name.
 * Fixes size in getting thumbnails, now checks for all available
 sizes and defaults ("thumbnail", "full", etc.)

= 0.41.0 =
 * Adds options page, to set the default numberposts value globally.
 * Adds `customfield_display_name` param.
 * Adds pagination to custom template.
 * Fixes date display.
 * Adds conditions to Vagrantfile to boot faster and not repeat work.
 * Fixes exclude posts, broken when migrating from get_posts to
 WP_Query.

= 0.40.1 =

 * Small fix closing quotes on content when using <!--more-->

= 0.40 =

 * Tested with WordPress 3.8
 * Removes unnecessary stuff on wp_enqueue_styles
 * Fixes validation when using quotes in title
 * Fixes on <!--more--> tag
 * Fixes on title HTML tag and CSS class. (*See HTML & CSS
 Customization* on [Other Notes](http://wordpress.org/plugins/list-category-posts/other_notes/) to check the expected behaviour)

= 0.39 =

 * Adds "post suffix" parameter, to add a String after each post
   listed. [Source](http://wordpress.org/support/topic/hack-post-title-adding-elements)

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
