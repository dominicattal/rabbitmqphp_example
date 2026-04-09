#!/bin/bash

useradd -r -s /bin/false madd_system
project_dir=$(pwd)
ln -snf "$project_dir" /var/www/madd
chmod +x /home/$SUDO_USER
chmod 755 "$project_dir"
chmod 755 "$project_dir"/*
for file in "$project_dir"/*.service; do
	cp "$file" /etc/systemd/system/
	file=$(basename "$file")
	systemctl daemon-reload
	systemctl enable "$file"
	systemctl restart "$file"
	systemctl status "$file"
done

