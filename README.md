# List Category Posts
[![Code Climate](https://codeclimate.com/github/picandocodigo/List-Category-Posts.png)](https://codeclimate.com/github/picandocodigo/List-Category-Posts)
[![Travis CI](https://travis-ci.org/picandocodigo/List-Category-Posts.svg)](https://travis-ci.org/picandocodigo/List-Category-Posts)

List Category Posts is a **[WordPress](http://wordpress.org) plugin**
that allows you to list posts from a category into a post/page using
the [catlist] shortcode. This shortcode accepts a category name or id,
the order in which you want the posts to display, and the number of
posts to display. You can use [catlist] as many times as needed with
different arguments.

Usage:
`[catlist argument1=value1 argument2=value2]`

Please visit the
[plugin's page on WordPress.org](http://wordpress.org/extend/plugins/list-category-posts/)
to find out more.

# Want to help with the development?

The evolution of this plugin wouldn't be possible without the help of
[these awesome contributors](https://github.com/picandocodigo/List-Category-Posts/graphs/contributors).
Special thanks to [Sophist](https://github.com/Sophist-UK) and
[bibz](https://github.com/bibz) for the
hard work :+1:

Currently the plugin is mostly being developed through code
contributions by its users. If you know PHP and would like to start
contributing to the plugin, let me know! I can help you get started.
Contact me [here](http://picandocodigo.net/about/contacto/).

# Development

A Vagrantfile is provided to set up a box for WordPress development.
You need [Vagrant](http://www.vagrantup.com/) installed on your
computer.

Fork the repo, clone it locally and do `vagrant up`. You'll have a
WordPress instance running on http://127.0.0.1:8080/.

WordPress is installed with this information:

 * Database Name: `wordpress`
 * User Name: `wordpressuser`
 * Password: `wordpresspass`

You can access the admin dashboard with the following credentials:

 * Admin user: `adminuser`
 * Admin password: `adminpass`

Activate the plugin on
http://localhost:8080/wp-admin/plugins.php

The plugin code is linked directly on the box, so any change you make
on the code is reflected automatically on the Vagrant box's WordPress.

## Testing

PHPUnit has been setup by [bibz](https://github.com/bibz), you can run `phpunit` on the Virtual Machine:

```bash
vagrant@vagrant-ubuntu-precise-32:/vagrant$ phpunit
Installing...
Running as single site... To run multisite, use -c tests/phpunit/multisite.xml
Not running ajax tests. To execute these, use --group ajax.
Not running ms-files tests. To execute these, use --group ms-files.
Not running external-http tests. To execute these, use --group external-http.
PHPUnit 4.7.3 by Sebastian Bergmann and contributors.

.

Time: 3.11 seconds, Memory: 46.25Mb

OK (1 test, 12 assertions)
```

The code needs lots of refactoring and probably some more documentation to be able to write some more relevant tests. This is a task where you can really help the development of the plugin if you're interested in contributing.

### Usage

`[catlist argument1=value1 argument2=value2]`

### Support the plugin

If you've found the plugin useful, consider making a [donation via PayPal](http://picandocodigo.net/programacion/wordpress/list-category-posts-wordpress-plugin-english/#support "Donate via PayPal") or visit my Amazon Wishlist for [books](http://www.amazon.com/gp/registry/wishlist/2HU1JYOF7DX5Q/ref=wl_web "Amazon Wishlist") or [comic books](http://www.amazon.com/registry/wishlist/1LVYAOJAZQOI0/ref=cm_wl_rlist_go_o) :)

## License
__[GPLv2](http://www.gnu.org/licenses/gpl-2.0.html)__

```
List Category Posts

Copyright (C) 2008-2016  Fernando Briano (email : fernando@picandocodigo.net)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
```
