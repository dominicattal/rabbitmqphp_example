#!/bin/bash

tar - xvf testTar.tar test
mysql -u db_user -p"$(cat pass.txt)" it490 < test/test.sql
