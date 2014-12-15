Zend ToDo
=========

Example application for 
[Software Architecture Project](http://www.cs.helsinki.fi/courses/582663/2014/s/k/1).

This is a simple PHP-Zend Framework 2 application for
managing ToDo lists.


## Install

If you are using [Vagrant](https://www.vagrantup.com/downloads.html), 
you can use `vagrant up` to install project.

### Manual install 

You will need at least:

* PHP (>=5.3.3)
* MySql
* Apache (recommended)

Create database `zend_webapp` and user for it. Create database tables with files located in
migrations folder. 
Create local config and add database user and password in to it:

```
// file config/autoload/local.php

<?php

return array(
    'db' => array(
        'username' => 'your_username',
        'password' => 'your_password',
    ),
);

```

Download [Composer](https://getcomposer.org/download/) and run `php composer.phar install`.
