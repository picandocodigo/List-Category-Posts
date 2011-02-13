Templates for the List Category Plugin are searched for in your WordPress theme's folder. You should create a folder named "list-category-posts" under 'wp-content/themes/your-theme-folder'.

Template files are .php files.

You can use the included template as an example to start. You can find it in the plugin's template folder under the name default.php. To use a template, use this code:
[catlist id=1 template=templatename]
If the template file were templatename.php.

You can have as many different templates as you want, and use them in different pages and posts. The template code is pretty well documented, so if you're a bit familiar with HTML and PHP, you'll have no problems creating your own template. I'm planning on reworking the template system in order to have a really user friendly way to create templates, but in the meantime, I'm slowly improving the existing system.

More info / help:
http://picandocodigo.net/programacion/wordpress/list-category-posts-wordpress-plugin-english/