#!/usr/bin/env bash

# Vagrant install Zend project script.
# Run as non privileged user

MYSQL_ROOT_PASSWORD=$1

# Create database for project
echo "create database zend_webapp" | mysql -uroot -p"$MYSQL_ROOT_PASSWORD"


# Run migrations
mysql -uroot -p"$MYSQL_ROOT_PASSWORD" zend_webapp < /vagrant/migrations/001_create_todo_tables.sql


# Create local Zend config
touch /vagrant/config/autoload/local.php
cat > /vagrant/config/autoload/local.php <<EOL
<?php

return array(
    'db' => array(
        'username' => 'root',
        'password' => '$MYSQL_ROOT_PASSWORD',
    ),
);
EOL


# Download composer
cd /vagrant/
curl -sS https://getcomposer.org/installer | php


# Use it to install dependencies
php composer.phar install


echo "###############################################"
echo "################ Installation complete ########"
echo "################ Open http://localhost:13000 ##"
echo "###############################################"