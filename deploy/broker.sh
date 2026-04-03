#!/bin/bash

cp /var/lib/rabbitmq/.erlang.cookie $HOME
chmod +r ./.erlang.cookie
rabbitmq-plugins enable rabbitmq_management

rabbitmqctl add_vhost it490
rabbitmqctl add_user broker_admin broker_admin
rabbitmqctl set_user_tags broker_admin administrator
rabbitmqctl  --vhost=it490 set_permissions broker_admin ".*" ".*" ".*"

rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare exchange name="deploy" type="direct"

rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare queue name="main_queue"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare binding source="deploy" destination="main_queue" routing_key="main"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare queue name="deploy_listen_queue"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare binding source="deploy" destination="deploy_listen_queue" routing_key="deploy_listen"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare queue name="deploy_queue"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare binding source="deploy" destination="deploy_queue" routing_key="deploy"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare queue name="dev_web_listen_queue"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare binding source="deploy" destination="dev_web_listen_queue" routing_key="dev_web_listen"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare queue name="dev_db_listen_queue"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare binding source="deploy" destination="dev_db_listen_queue" routing_key="dev_db_listen"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare queue name="dev_data_listen_queue"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare binding source="deploy" destination="dev_data_listen_queue" routing_key="dev_data_listen"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare queue name="qa_web_listen_queue"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare binding source="deploy" destination="qa_web_listen_queue" routing_key="qa_web_listen"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare queue name="qa_db_listen_queue"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare binding source="deploy" destination="qa_db_listen_queue" routing_key="qa_db_listen"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare queue name="qa_data_listen_queue"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare binding source="deploy" destination="qa_data_listen_queue" routing_key="qa_data_listen"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare queue name="prod_web_listen_queue"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare binding source="deploy" destination="prod_web_listen_queue" routing_key="prod_web_listen"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare queue name="prod_db_listen_queue"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare binding source="deploy" destination="prod_db_listen_queue" routing_key="prod_db_listen"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare queue name="prod_data_listen_queue"
rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare binding source="deploy" destination="prod_data_listen_queue" routing_key="prod_data_listen"

#rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare queue name="main_deploy_queue"
#rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare queue name="deploy_main_queue"
#rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare binding source="deploy" destination="main_deploy_queue" routing_key="main_deploy"
#rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare binding source="deploy" destination="deploy_main_queue" routing_key="deploy_main"

#rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare queue name="dev_web_queue"
#rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare queue name="dev_db_web_queue"
#rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare queue name="dev_db_data_queue"
#rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare queue name="dev_data_queue"
#rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare binding source="deploy" destination="dev_web_queue" routing_key="dev_web"
#rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare binding source="deploy" destination="dev_db_web_queue" routing_key="dev_db_web"
#rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare binding source="deploy" destination="dev_db_data_queue" routing_key="dev_db_data"
#rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare binding source="deploy" destination="dev_data_queue" routing_key="dev_data"
#
#rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare queue name="qa_web_queue"
#rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare queue name="qa_db_web_queue"
#rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare queue name="qa_db_data_queue"
#rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare queue name="qa_data_queue"
#rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare binding source="deploy" destination="qa_web_queue" routing_key="qa_web"
#rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare binding source="deploy" destination="qa_db_web_queue" routing_key="qa_db_web"
#rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare binding source="deploy" destination="qa_db_data_queue" routing_key="qa_db_data"
#rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare binding source="deploy" destination="qa_data_queue" routing_key="qa_data"
#
#rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare queue name="prod_web_queue"
#rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare queue name="prod_db_web_queue"
#rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare queue name="prod_db_data_queue"
#rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare queue name="prod_data_queue"
#rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare binding source="deploy" destination="prod_web_queue" routing_key="prod_web"
#rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare binding source="deploy" destination="prod_db_web_queue" routing_key="prod_db_web"
#rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare binding source="deploy" destination="prod_db_data_queue" routing_key="prod_db_data"
#rabbitmqadmin --username=broker_admin --password=broker_admin --vhost=it490 declare binding source="deploy" destination="prod_data_queue" routing_key="prod_data"
