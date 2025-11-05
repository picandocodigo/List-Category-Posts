=== List category posts ===
Contributors: fernandobt, zymeth25
Donate Link: http://picandocodigo.net/programacion/wordpress/list-category-posts-wordpress-plugin-english/#support
Tags: list, categories, posts, cms
Requires at least: 3.3
Tested up to: 6.8.3
Requires PHP: 5.6
Stable tag: 0.93.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Very customizable plugin to list posts by category (or tag, author and more) in a post, page or widget. Uses the [catlist] shortcode to select posts.

== Description ==

List Category Posts allows you to list posts by category in a post or page using the `[catlist]` shortcode. When you're editing a page or post, directly insert the shortcode in your text and the posts will be listed there. The *basic* usage would be something like this:

`[catlist id=1]`

`[catlist name="news"]`

The shortcode accepts a category name or id, the order in which you want the posts to display, and the number of posts to display. You can also display the post author, date, excerpt, custom field values, even the content! A lot of parameters have been added to customize what to display and how to show it. Check [the full documentation](https://github.com/picandocodigo/List-Category-Posts/wiki) to learn about the different ways to use it.

The `[catlist]` shortcode can be used as many times as needed with different arguments on each post/page.
`[catlist id=1 numberposts=10]`

There's an options page with a few options, new options will be implemented on demand (as long as they make sense).

**[Read the instructions](https://github.com/picandocodigo/List-Category-Posts/wiki)** to learn which parameters are available and how to use them.

If you want to **List Categories** instead of posts you can use my other plugin **[List categories](http://wordpress.org/plugins/list-categories/)**.

You can find **Frequently Asked Questions** [here](https://github.com/picandocodigo/List-Category-Posts/blob/master/doc/FAQ.md#frequently-asked-questions).

**Customization**

The different elements to display can be styled with CSS. you can define an HTML tag to wrap the element with, and a CSS class for this tag. Check [the documentation](https://github.com/picandocodigo/List-Category-Posts/wiki) for usage. You can also check [this nice tutorial](http://sundari-webdesign.com/wordpress-the-quest-to-my-perfect-list-view-for-posts-events-and-articles/) which gives lots of tips and how-to's to customize how to display the posts.

Great to use WordPress as a CMS, and create pages with several categories posts.

**GUI**

Klemens Starybrat has created a GUI for List Category Posts. It helps you create a shortcode from a nice visual interface in WordPress' text editor. Check it out:
[GUI for List Category Posts](https://wordpress.org/plugins/gui-for-lcp/)

**AJAX pagination**

The ajax pagination feature is maintained in an add-on plugin by Klemens Starybrat. Check it out:
[LCP Ajax Pagination](https://wordpress.org/plugins/lcp-ajax-pagination)

**Widget**

Since WordPress 4.9, [you can use shortcode in text widgets](https://make.wordpress.org/core/2017/10/24/widget-improvements-in-wordpress-4-9/). So you can just add a text widget in Appearence > Widgets and write the List Category Posts shortcode.

The plugin also includes a widget as a simple interface for its functionality. Just add as many widgets as you want, and select all the available options from the Appearence > Widgets page. Not all the functionality in the shortcode has been implemented in the widget yet. You can use the shortcode for the most flexibility.

Please, read the information on [the wiki](https://github.com/picandocodigo/List-Category-Posts/wiki) and [Changelog](https://wordpress.org/plugins/list-category-posts/#developers) to be aware of new functionality, and improvements to the plugin.

**Videos**

Some users have made videos on how to use the plugin (thank you, you are awesome!), check them out here:

 * [Manage WordPress Content with List Category Posts Plugin](http://www.youtube.com/watch?v=kBy_qoGKpdo)
 * [WordPress: How to List Category Posts on a Page](http://www.youtube.com/watch?v=Zfnzk4IWPNA)

**Support the plugin**

Klemens Starybrat has been writing lots of amazing code for this plugin, so if you've found it useful and want to pay it forward, consider sponsoring him on GitHub: https://github.com/sponsors/klemens-st

I have a [PayPal account](http://picandocodigo.net/programacion/wordpress/list-category-posts-wordpress-plugin-english/#support "Donate via PayPal") where you can donate too.


**Development**

Development is being tracked on [Codeberg](https://codeberg.org/picandocodigo/list-category-posts) and [GitHub](https://github.com/picandocodigo/List-Category-Posts). Fork it, code, make a pull request, suggest improvements, etc. over there. I dream of the day all of the WordPress plugins will be hosted on Git :)

Since the documentation on how to use the plugin has passed wordpress.org's character limit, the text was cut. You can find the complete documentation on [the wiki](https://github.com/picandocodigo/List-Category-Posts/wiki). It's also more comfortable to read and maintain than a txt file. Please check it out, suggestions are welcome on Codeberg/GitHub issues!

==Instructions on how to use the plugin==

Below you can find common shortcode use cases. You can use the shortcode while editing posts, pages, custom post types, text widgets and in all "page builder" plugins that support shortcodes.

Read the **[full documentation](https://github.com/picandocodigo/List-Category-Posts/wiki)** to discover many more features, including:

* **advanced post selection options** (by date, search terms, custom fields, post types, custom taxonomies and more)
* **output customizations** (show each post's date, author, excerpt, thumbnail and more)
* **custom templates** of your own design, based on a simple PHP example

List 10 latest posts:

`[catlist]`

The default number of listed posts is 10, to modify it you need to specify `numberposts` parameter:

`[catlist numberposts=15]`

List posts from the "Dogs" category:

`[catlist name=Dogs]`

List posts from the category with id `2`:

`[catlist id=2]`

By default only the "post" post type is included. To list pages use:

`[catlist post_type=page]`

and for both posts and pages:

`[catlist post_type="post,page"]`

If we combine the above options we can get a shortcode that lists 15 items, including post and pages, from the "Dogs" category:

`[catlist name=Dogs post_type="post,page" numberposts=15]`

Most of the parameters you will find in the documentation can be used together.

The plugin can detect current post's category and use it for listing:

`[catlist categorypage=yes]`

Same goes for tags:

`[catlist currenttags=yes]`

To show each post's excerpt use:

`[catlist excerpt=yes]`

If you want to show the date, author and comments count next to each post, use:

`[catlist date=yes author=yes comments=yes]`

You can specify html tags and classes for many elements. Let's modify the previous shortcode and wrap dates and authors in tags and classes of our choice:

`[catlist date=yes date_tag=span date_class="my-date" author=yes author_tag=p author_class="my-author" comments=yes]`

[Read more about this feature](https://github.com/picandocodigo/List-Category-Posts/wiki/HTML-&-CSS-Customization)

You can order posts by date:

`[catlist orderby=date]`

You can also use `title`, `author`, `ID`. More options are described in the documentation.

The plugin also supports pagination. You need to specify `numberposts` to tell the plugin how many posts per page you want:

`[catlist pagination=yes numberposts=5]`

See the wiki: [Pagination](https://github.com/picandocodigo/List-Category-Posts/wiki/Pagination) for more information.

Please read the **[full documentation](https://github.com/picandocodigo/List-Category-Posts/wiki)** for detailed documentation of all plugin features, use cases and shortcode parameters.

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

How to customize the way the posts are shown: [Template System](https://github.com/picandocodigo/List-Category-Posts/wiki/Template-System).

**NEW FEATURE REQUESTS, BUG FIXES, ENHANCEMENTS**

You can post them on [GitHub Issues](https://github.com/picandocodigo/List-Category-Posts/issues).

**FURTHER QUESTIONS**

Please check the [FAQ](https://github.com/picandocodigo/List-Category-Posts/blob/master/doc/FAQ.md#frequently-asked-questions) before posting a question. You can post questions in the [Support forum](http://wordpress.org/support/plugin/list-category-posts) or [add a new issue on GitHub](https://github.com/picandocodigo/List-Category-Posts/issues).

== Upgrade Notice ==

= 0.92.0 =

- Template files when using the `template` parameter can only have letters, numbers, `_` and `-` in the name. They also can only be located in the current theme's directory under a `list-category-posts` directory.

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

See [CHANGELOG.md](https://github.com/picandocodigo/List-Category-Posts/blob/master/CHANGELOG.md) for full Changelog.

= 0.93.1 =

* Fixes a bug with `post_status` introduced in `sanitize_status`. Thanks Galen Charlton (@gmcharlt) for the catch and fix!

= 0.93.0 =

* Don't skip password protected filter when showing content.
* Sanitize post_status so some posts are only shown if user is Editor or Administrator.
* Addresses reported vulnerability: CVE-2025-11377, Authenticated (Contributor+) Information Exposure. Severity Score: 4.3 (Medium). CVSS Vector: CVSS:3.1/AV:N/AC:L/PR:L/UI:N/S:U/C:L/I:N/A:N. Organization: Wordfence. Vulnerability Researcher(s): Athiwat Tiprasaharn (Jitlada)

This is a low risk vulnerability that could potentially be executed by an authenticated attacker, with contributor-level access and above. But it should be fixed with this version.

= 0.92.0 =

* Avoids potential SQL injection in `starting_with` parameter - CVE-2025-10163. This solves SQL injection and results in `starting_with` working as per the Wiki, but the previous code also allowed things like `[catlist starting_with="Hello"]` which would return posts starting with "Hello" but not just with "H". This new implementation would return both, because only the first character matters, which is ok because that's what is documented.
* Improves template file inclusion security. Template files when using the `template` parameter can only have letters, numbers, `_` and `-` in the name. They also can only be located in the current theme's directory under a `list-category-posts` directory.

= 0.91.0 =

* Addresses CVE-2025-47636, avoids Local File Inclusion for template system. The code will remove any occurrences of the string  '../' in the template parameter. Templates files must be php files located in a directory named `list-category-posts` under `wp-content/themes/your-theme-folder`.
https://www.cve.org/CVERecord?id=CVE-2025-47636

= 0.90.3 =

* Hardens xss fix for script tag by checking case insensitive and using tag_escape.

= 0.90.2 =

* Updates fix for stored cross-site scripting from 0.90.0, now applied to all tags. From this version onwards, script is not available to use as a tag when setting an element's tag in the shortcode.

= 0.90.1 =

* Fix PHP 8.2 deprecation notices
* Remove empty anchor tags from widget morelink

= 0.90.0 =

* Fixes a Stored Cross-Site Scripting issue using `excerpt_tag='script'`.
