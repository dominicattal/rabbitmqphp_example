#!/bin/bash

cp /var/lib/rabbitmq/.erlang.cookie $HOME
chmod +r ./.erlang.cookie
rabbitmq-plugins enable rabbitmq_management

rabbitmqctl add_vhost cluster_vhost
rabbitmqctl add_user broker_admin broker_admin
rabbitmqctl set_user_tags broker_admin administrator

rabbitmqctl  --vhost=cluster_vhost set_permissions broker_admin ".*" ".*" ".*"

rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=cluster_vhost declare exchange name="log_exchange" type="fanout"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=cluster_vhost declare queue name="log_listen_queue"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=cluster_vhost declare binding source="log_exchange" destination="log_listen_queue" routing_key="#"
