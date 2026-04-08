#!/bin/bash 

mysql -e "create database if not exists bundles;"
mysql 'bundles' -e "create user if not exists 'db_user'@'localhost' identified by 'db_pass';"
mysql 'it490' -e "grant all on branches.* to 'db_user'@'localhost';"
mysql 'it490' < schema_deployment.sql

