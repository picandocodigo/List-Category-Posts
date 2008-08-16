=== List category posts ===
Contributors: fernandobt
Donate Link: http://picandocodigo.net/programacion/wordpress/list-category-posts-wordpress-plugin-english/
Tags: list, categories, posts, cms
Requires at least: 2.0
Tested up to: 2.6
Stable tag: 0.1

== Description ==
List Category Posts is a simple WordPress plugin which allows you to list some posts from a category into a post/page using [catlist=ID], where ID stands for the Category Id. You can list several categories on the same page/post. You can use [catlist=ID] as many times as needed with different Id’s. You may also define a limit of posts to show.
Great to use WordPress as a CMS, and create pages with several categories posts.

Inspired by Category Page: http://wordpress.org/extend/plugins/page2cat/
Category Page is a good plugin, but too complicated and big for what I needed. I just needed to list posts from a certain category, and be able to use several category id's to list on one page.

List category posts was written with Geany - http://geany.uvena.de/

==Installation==

    * Upload listcat directory into you wp-content/plugins/ directory.
    * Login to your WordPress Admin menu, go to Plugins, and activate it.
    * On Settings / Category List, input the post limit (how many posts you want it to display). Default is 5.
    * Add “lcp_catlist” class into your theme’s CSS for custom formatting.

==Usage==

When writing a post/page, use [catlist=ID] where ID is the Id for a specific category. You can change the way List Category Posts shows the posts in your CSS by editing "lcp_catlist". The generated code is: <ul class="lcp_catlist">, and a <li> for each post in the category.