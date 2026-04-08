#!/bin/bash

apt install php rabbitmq-server php-amqp apache2 libapache2-mod-php php-mysql php-curl curl

project_dir=$(pwd)
ln -sf "$project_dir" /var/www/madd
chmod +x /home/$SUDO_USER
chmod -R 755 "$project_dir"
cp "$project_dir/apache.conf" /etc/apache2/sites-available/apache.conf
a2ensite apache.conf
systemctl reload apache2
