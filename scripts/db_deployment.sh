#!/bin/bash 

mysql -e "create database if not exists bundles;"
mysql 'bundles' -e "create user if not exists 'db_user'@'localhost' identified by 'db_pass';"
mysql 'bundles' -e "grant all on bundles.* to 'db_user'@'localhost';"
mysql 'bundles' < schema_deployment.sql

