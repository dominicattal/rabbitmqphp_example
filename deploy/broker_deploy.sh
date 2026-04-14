#!/bin/bash

cp /var/lib/rabbitmq/.erlang.cookie $HOME
chmod +r ./.erlang.cookie
rabbitmq-plugins enable rabbitmq_management

rabbitmqctl add_vhost deploy_vhost
rabbitmqctl add_user broker_admin broker_admin
rabbitmqctl set_user_tags broker_admin administrator
rabbitmqctl  --vhost=deploy_vhost set_permissions broker_admin ".*" ".*" ".*"

rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=deploy_vhost declare exchange name="deploy_exchange" type="direct"

rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=deploy_vhost declare queue name="main_queue"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=deploy_vhost declare binding source="deploy_exchange" destination="main_queue" routing_key="main"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=deploy_vhost declare queue name="deploy_listen_queue"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=deploy_vhost declare binding source="deploy_exchange" destination="deploy_listen_queue" routing_key="deploy_listen"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=deploy_vhost declare queue name="deploy_queue"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=deploy_vhost declare binding source="deploy_exchange" destination="deploy_queue" routing_key="deploy"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=deploy_vhost declare queue name="dev_web_listen_queue"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=deploy_vhost declare binding source="deploy_exchange" destination="dev_web_listen_queue" routing_key="dev_web_listen"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=deploy_vhost declare queue name="dev_db_listen_queue"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=deploy_vhost declare binding source="deploy_exchange" destination="dev_db_listen_queue" routing_key="dev_db_listen"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=deploy_vhost declare queue name="dev_data_listen_queue"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=deploy_vhost declare binding source="deploy_exchange" destination="dev_data_listen_queue" routing_key="dev_data_listen"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=deploy_vhost declare queue name="qa_web_listen_queue"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=deploy_vhost declare binding source="deploy_exchange" destination="qa_web_listen_queue" routing_key="qa_web_listen"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=deploy_vhost declare queue name="qa_db_listen_queue"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=deploy_vhost declare binding source="deploy_exchange" destination="qa_db_listen_queue" routing_key="qa_db_listen"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=deploy_vhost declare queue name="qa_data_listen_queue"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=deploy_vhost declare binding source="deploy_exchange" destination="qa_data_listen_queue" routing_key="qa_data_listen"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=deploy_vhost declare queue name="prod_web_listen_queue"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=deploy_vhost declare binding source="deploy_exchange" destination="prod_web_listen_queue" routing_key="prod_web_listen"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=deploy_vhost declare queue name="prod_db_listen_queue"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=deploy_vhost declare binding source="deploy_exchange" destination="prod_db_listen_queue" routing_key="prod_db_listen"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=deploy_vhost declare queue name="prod_data_listen_queue"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=deploy_vhost declare binding source="deploy_exchange" destination="prod_data_listen_queue" routing_key="prod_data_listen"

