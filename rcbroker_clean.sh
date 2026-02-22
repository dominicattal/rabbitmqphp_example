#!/bin/bash

rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 delete exchange name="broker_exchange"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 delete queue name="web_to_broker_queue"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 delete queue name="broker_to_db_queue"

rabbitmqctl delete_user broker_admin
rabbitmqctl delete_user broker_user 
rabbitmqctl delete_user web_user 
rabbitmqctl delete_user db_user 

rabbitmqctl delete_vhost it490
