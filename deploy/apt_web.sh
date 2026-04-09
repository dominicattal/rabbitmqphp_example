#!/bin/bash

apt install php rabbitmq-server php-amqp apache2 libapache2-mod-php php-mysql php-curl curl

project_dir=$(pwd)
mkdir -p "$project_dir/sample"
#mv *.php *.html *.css *.jpg "$project_dir/sample/"
ln -snf "$project_dir" /var/www/madd
chmod +x /home/$SUDO_USER
chmod -R 755 "$project_dir"
cp "$project_dir/apache.conf" /etc/apache2/sites-available/apache.conf
a2ensite apache.conf
systemctl reload apache2
systemctl status apache2
