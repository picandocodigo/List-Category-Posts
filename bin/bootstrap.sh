#!/usr/bin/env bash

# Update the system and install SVN + PHP 8.2 + MySQL 8
if [ ! -x /usr/bin/mysql ];
then
    sudo debconf-set-selections <<< 'mysql-server-8.0 mysql-server/root_password password rootpass'
    sudo debconf-set-selections <<< 'mysql-server-8.0 mysql-server/root_password_again password rootpass'
    sudo add-apt-repository ppa:ondrej/php

    apt-get update

    apt-get install -y subversion apache2 php8.2 mysql-server dos2unix unzip
    apt-get install -y php8.2-{xml,readline,opcache,mysql,imagick,zip,mbstring,curl}

fi

# Create the WordPress database and corresponding user
if [ ! -f /var/log/databasesetup ];
then
    echo "CREATE USER 'wordpressuser'@'localhost' IDENTIFIED BY 'wordpresspass'" | mysql -uroot -prootpass
    echo "CREATE DATABASE wordpress" | mysql -uroot -prootpass
    echo "GRANT ALL ON wordpress.* TO 'wordpressuser'@'localhost'" | mysql -uroot -prootpass
    echo "flush privileges" | mysql -uroot -prootpass

    touch /var/log/databasesetup
fi

# Enable the default apache site located in /var/www
if [ ! -f /var/log/webserversetup ];
then
    a2enmod rewrite
    sedcmd='/var\/www/ c\DocumentRoot /var/www\
       <Directory /var/www>\
       AllowOverride All\
       </Directory>'
    sed -i "$sedcmd" /etc/apache2/sites-available/000-default.conf
    service apache2 restart

    touch /var/log/webserversetup
fi

# Install the wp-cli utility
if [ ! -x /usr/local/bin/wp ];
then
    cd /usr/local/bin
    wget -O wp https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
    chmod a+x wp
fi

# Install WordPress, configure it and import the test data
if [ ! -f /var/www/wordpress ];
then
    cd /var/www
    sudo rm -rf *
    sudo chown www-data:www-data .
    sudo -u www-data wp core download --version=latest
    sudo -u www-data wp core config --dbname=wordpress --dbuser=wordpressuser --dbpass=wordpresspass
    sudo -u www-data wp config set WP_DEBUG true --type=constant
    sudo -u www-data wp config set WP_DEBUG_LOG true --type=constant
    sudo -u www-data wp core install --url="http://localhost:8080" --title="Testing the LCP plugin" --admin_user=adminuser --admin_password=adminpass --admin_email="admin@example.com"
    sudo -u www-data wp plugin install wordpress-importer --activate
    sudo -u www-data wp plugin install classic-editor --activate
    sudo -u www-data wget https://raw.githubusercontent.com/manovotny/wptest/master/wptest.xml
    sudo -u www-data wp import wptest.xml --authors=create
    sudo rm wptest.xml
    sudo ln -s /vagrant/ /var/www/wp-content/plugins/list-category-posts
    sudo touch wordpress
fi

# Install Composer
if [ ! -x /usr/local/bin/composer ];
then
    cd /usr/local/bin

    EXPECTED_CHECKSUM="$(php -r 'copy("https://composer.github.io/installer.sig", "php://stdout");')"
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', 'composer-setup.php');")"

    if [ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ]
    then
        >&2 echo 'ERROR: Invalid installer checksum'
        rm composer-setup.php
        exit 1
    fi

    php composer-setup.php --quiet

    rm composer-setup.php
    mv composer.phar composer
fi

# Install PHPUnit & PHPUnit Polyfills
if [ ! -x /home/vagrant/.config/composer/vendor/bin/phpunit ];
then
    sudo -u vagrant composer global require phpunit/phpunit ~9 --ignore-platform-reqs --with-all-dependencies
    # https://make.wordpress.org/core/2021/09/27/changes-to-the-wordpress-core-php-test-suite/#integration-tests-ci-changes
    sudo -u vagrant composer global require yoast/phpunit-polyfills --ignore-platform-reqs

    sudo -u vagrant echo 'PATH="$PATH:$HOME/.config/composer/vendor/bin"' >> /home/vagrant/.profile
fi

# Set WP_TESTS_DIR
if [ ! -x /etc/profile.d/wp-tests-dir.sh ];
then
    cd /etc/profile.d
    echo "export WP_TESTS_DIR=/var/www/wp-tests-lib" > wp-tests-dir.sh
fi

# Initiate the testing framework
if [ -x /home/vagrant/.config/composer/vendor/bin/phpunit -a -f /var/www/wordpress ];
then
    cd /var/www/wp-content/plugins/list-category-posts

    # For Windows users make sure the script is in unix format before running bash.
    dos2unix bin/install-wp-tests.sh

    sudo -u www-data WP_TESTS_DIR=/var/www/wp-tests-lib WP_CORE_DIR=/var/www/ bash bin/install-wp-tests.sh wordpress_test root rootpass localhost latest
fi
