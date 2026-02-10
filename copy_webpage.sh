#!/bin/bash

DIR="/var/www/sample"
FILES="sample/login.php sample/registration.php sample/home.php"

echo "Copying files..."
for file in $FILES; do
    echo $file
    cp $file $DIR
done;
