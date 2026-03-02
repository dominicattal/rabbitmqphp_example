#!/bin/bash

rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 delete binding source="broker_exchange" destination_type="direct" destination="db_queue" 
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 delete binding source="broker_exchange" destination_type="direct" destination="web_queue" 
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 delete binding source="broker_exchange" destination_type="direct" destination="data_queue" 
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 delete queue name="db_queue"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 delete queue name="web_queue"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 delete queue name="data_queue"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 delete exchange name="broker_exchange"

rabbitmqctl delete_user broker_admin
rabbitmqctl delete_user broker_user 
rabbitmqctl delete_user web_user 
rabbitmqctl delete_user db_user 
rabbitmqctl delete_user data_user 

rabbitmqctl delete_vhost it490
