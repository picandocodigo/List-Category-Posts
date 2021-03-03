# List Category Posts
[![Code Climate](https://codeclimate.com/github/picandocodigo/List-Category-Posts.png)](https://codeclimate.com/github/picandocodigo/List-Category-Posts) [![Tests](https://github.com/picandocodigo/List-Category-Posts/actions/workflows/master.yml/badge.svg)](https://github.com/picandocodigo/List-Category-Posts/actions/workflows/master.yml)

List Category Posts is a **[WordPress](http://wordpress.org) plugin** that allows you to list posts from a category into a post/page using the [catlist] shortcode. This shortcode accepts a category name or id, the order in which you want the posts to display, and the number of posts to display. You can use [catlist] as many times as needed with different arguments.

Usage:
`[catlist argument1=value1 argument2=value2]`

Please visit the [plugin's page on WordPress.org](http://wordpress.org/extend/plugins/list-category-posts/) to find out more.

# Would you like to help with the development?

The evolution of this plugin wouldn't be possible without the help of [these awesome contributors](https://github.com/picandocodigo/List-Category-Posts/graphs/contributors). Special thanks to [Sophist](https://github.com/Sophist-UK), [bibz](https://github.com/bibz), [vacuus](https://github.com/vacuus), [zymeth25](https://github.com/zymeth25) for their contributions and hard work and [every other person](https://github.com/picandocodigo/List-Category-Posts/graphs/contributors) who's contributed to this plugin:+1:

Sometimes Pull Request take a while for us to review, but we'll eventually get to all of them. If you open a PR, feel free to add your wordpress.org user so if it gets merged I can add you to the list of contributors in the plugin's readme file.

# Development

A Vagrantfile is provided to set up a box for WordPress development. You need [Vagrant](http://www.vagrantup.com/) installed on your computer.

Fork the repo, clone it locally and do `vagrant up` (on Windows you need to run this command in PowerShell as administrator).
You'll have a WordPress instance running on http://127.0.0.1:8080/.

WordPress is installed with this information:

 * Database Name: `wordpress`
 * User Name: `wordpressuser`
 * Password: `wordpresspass`

You can access the admin dashboard with the following credentials:

 * Admin user: `adminuser`
 * Admin password: `adminpass`

Activate the plugin on
http://localhost:8080/wp-admin/plugins.php

The plugin code is linked directly on the box, so any change you make on the code is reflected automatically on the Vagrant box's WordPress installation.

## Testing

PHPUnit has been set up by [bibz](https://github.com/bibz), you can run `phpunit` on the Virtual Machine:

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

If you've found the plugin useful, consider buying us a beverage :)

- [Klemens Starybrat](https://github.com/zymeth25) has been writing lots of amazing code for this plugin, so if you want to pay it forward, [consider sponsoring him on GitHub](https://github.com/sponsors/zymeth25).

- You can also [sponsor me on GitHub](https://github.com/sponsors/picandocodigo), make a [donation via PayPal](http://picandocodigo.net/programacion/wordpress/list-category-posts-wordpress-plugin-english/#support "Donate via PayPal") or visit [my Amazon Wishlist](https://www.amazon.co.uk/hz/wishlist/ls/21UGAJCP8YEKU?ref_=wl_share).

## License
__[GPLv2](http://www.gnu.org/licenses/gpl-2.0.html)__

```
List Category Posts

Copyright (C) 2008-2020  Fernando Briano (email : fernando@picandocodigo.net)

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
