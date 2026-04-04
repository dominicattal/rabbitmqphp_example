#!/bin/bash

cp /var/lib/rabbitmq/.erlang.cookie $HOME
chmod +r ./.erlang.cookie
rabbitmq-plugins enable rabbitmq_management

rabbitmqctl add_vhost it490
rabbitmqctl add_user broker_admin broker_admin
rabbitmqctl set_user_tags broker_admin administrator

rabbitmqctl  --vhost=it490 set_permissions broker_admin ".*" ".*" ".*"

rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare exchange name="cluster_exchange" type="direct"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare queue name="web_queue"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare queue name="db_queue"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare queue name="db_listen_queue"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare queue name="data_listen_queue"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare binding source="cluster_exchange" destination="web_queue" routing_key="web"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare binding source="cluster_exchange" destination="db_queue" routing_key="db"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare binding source="cluster_exchange" destination="db_listen_queue" routing_key="db_listen"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare binding source="cluster_exchange" destination="data_listen_queue" routing_key="data_listen"
