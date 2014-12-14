#!/usr/bin/env bash

# Vagrant provision shell script.
# Installs needed packages to run
# Zend example application.

MYSQL_ROOT_PASSWORD=$1


# First set locales.
locale-gen en_US.UTF-8
update-locale LC_ALL=en_US.UTF-8 LANG=en_US.UTF-8 LC_MESSAGES=POSIX


# Update package index.
apt-get -y update


# Install some basic tools.
apt-get -y install curl git tree python-software-properties


# Zend framework requires php >=5.3.23, which is not available
# from ubuntu's package repository. Add third party PPA.
add-apt-repository -y ppa:ondrej/php5-oldstable
# Yeah. It is needed again.
apt-get -y update



# Install apache webserver.
apt-get -y install apache2
echo ServerName $HOSTNAME >> /etc/apache2/apache2.conf
# Enable mod_rewrite.
a2enmod rewrite
# Setup apache virtual host.
touch /etc/apache2/sites-available/zend-webapp
cat > /etc/apache2/sites-available/zend-webapp <<EOL
# See: http://framework.zend.com/manual/2.0/en/user-guide/skeleton-application.html#virtual-host

<VirtualHost *:80>
    DocumentRoot /vagrant/public
    SetEnv APPLICATION_ENV "development"
    <Directory /vagrant/public>
        DirectoryIndex index.php
        AllowOverride All
        Order allow,deny
        Allow from all
    </Directory>
</VirtualHost>
EOL
a2dissite default
a2ensite zend-webapp
service apache2 restart


# Install php.
apt-get -y install libapache2-mod-php5
# Install additional php modules.
apt-get -y install php5-curl php5-mcrypt


# Install mysql.
debconf-set-selections <<< "mysql-server mysql-server/root_password password $MYSQL_ROOT_PASSWORD"
debconf-set-selections <<< "mysql-server mysql-server/root_password_again password $MYSQL_ROOT_PASSWORD"
# Install the server with php modules.
apt-get -y install mysql-server libapache2-mod-auth-mysql php5-mysql


echo "###############################################"
echo "################ Provisioning complete ########"
echo "###############################################"
