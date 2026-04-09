#!/bin/bash
sudo mysqldump -u db_user -p"$(cat scripts/pass.txt)"  it490 > scripts/test/test.sql
echo "zipping up the DB!"

tar -cvf testTar.tar scripts/test/test.sql

scp testTar.tar matthew@100.111.93.122:~
