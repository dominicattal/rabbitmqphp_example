#!/bin/bash

mysql 'it490' -e "delete from movies;"
mysql 'it490' -e "delete from popular_movies;"
mysql 'it490' -e "delete from genres;"
