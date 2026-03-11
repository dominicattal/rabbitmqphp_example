#!/bin/bash

mysql -e "drop database it490;"
mysql -e "drop user 'db_user'@'localhost';"
