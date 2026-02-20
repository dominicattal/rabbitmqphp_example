#!/bin/bash

rabbitmqctl delete_user broker_user 
rabbitmqctl delete_user web_user 
rabbitmqctl delete_user db_user 
rabbitmqadmin delete exchange name="broker_exchange"
rabbitmqadmin delete queue name="web_to_broker_queue"
#rabbitmqadmin delete queue name="web_to_broker_queue_response"
rabbitmqadmin delete queue name="broker_to_db_queue"
#rabbitmqadmin delete queue name="broker_to_db_queue_response"
