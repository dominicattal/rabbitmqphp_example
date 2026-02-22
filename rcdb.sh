#!/bin/bash

mysql -e "create database it490;"
mysql 'it490' -e "create user 'db_user'@'localhost' identified by 'db_pass';"
mysql 'it490' -e "grant all on it490.* to 'db_user'@'localhost';"
mysql 'it490' < schema.sql
