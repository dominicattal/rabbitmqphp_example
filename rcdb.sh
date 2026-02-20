#!/bin/bash

mysql -e "create database it490;"
mysql 'it490' < schema.sql
