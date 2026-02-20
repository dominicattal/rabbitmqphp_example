#!/bin/bash

rabbitmqctl add_user broker_user broker_pass
rabbitmqctl set_permissions broker_user ".*" ".*" ".*"
rabbitmqctl add_user web_user web_pass
rabbitmqctl set_permissions web_user ".*" ".*" ".*"
rabbitmqctl add_user db_user db_pass
rabbitmqctl set_permissions db_user ".*" ".*" ".*"
rabbitmqadmin declare exchange name="broker_exchange" type="topic"
rabbitmqadmin declare queue name="web_to_broker_queue"
rabbitmqadmin declare queue name="broker_to_db_queue"
