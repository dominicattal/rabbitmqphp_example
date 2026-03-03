#!/bin/bash

rabbitmqctl add_vhost it490
rabbitmqctl add_user broker_admin broker_admin
rabbitmqctl set_user_tags broker_admin administrator
rabbitmqctl add_user broker_user broker_pass
rabbitmqctl add_user web_user web_pass
rabbitmqctl add_user db_user db_pass
rabbitmqctl add_user data_user data_pass

rabbitmqctl  --vhost=it490 set_permissions broker_admin ".*" ".*" ".*"
rabbitmqctl  --vhost=it490 set_permissions broker_user ".*" ".*" ".*"
rabbitmqctl  --vhost=it490 set_permissions web_user ".*" ".*" ".*"
rabbitmqctl  --vhost=it490 set_permissions db_user ".*" ".*" ".*"
rabbitmqctl  --vhost=it490 set_permissions data_user ".*" ".*" ".*"

rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare exchange name="broker_exchange" type="direct"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare queue name="web_queue"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare queue name="db_queue"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare queue name="data_queue"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare binding source="broker_exchange" destination="web_queue" routing_key="web"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare binding source="broker_exchange" destination="db_queue" routing_key="db"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare binding source="broker_exchange" destination="data_queue" routing_key="data"
