#!/bin/bash

DIR="/var/www/sample"
FILES="sample/login.html sample/registration.html sample/home.html sample/login_handler.php sample/registration_handler.php"

echo "Copying files..."
for file in $FILES; do
    echo $file
    cp $file $DIR
done;
