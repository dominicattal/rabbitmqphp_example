#!/bin/bash

apt update
apt install openssl ssl-cert ca-certificates -y
a2enmod ssl
mkdir -p /etc/apache2/ssl
sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout /etc/apache2/ssl/ssl.key \
    -out /etc/apache2/ssl/ssl.crt \
    # CHANGE THESE IP ADDRESSES TO YOURS
    -subj "/CN=100.73.150.33" \
    -addext "subjectAltName = IP:100.73.150.33"
chmod 644 /etc/apache2/ssl/ssl.key
chmod 644 /etc/apache2/ssl/ssl.crt
ufw allow 443/tcp
ufw reload
echo "Make sure the IP addresses are the correct ones!! OR ELSE!!"
