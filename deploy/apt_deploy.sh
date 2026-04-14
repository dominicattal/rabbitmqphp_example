#!/bin/bash

apt install mysql-client-core 
if [ $? -ne 0 ]; then
    apt install mysql-client-core-8.0 # works on darren vm but not on dom vm
fi
apt install php rabbitmq-server php-amqp mysql-server php-mysql

