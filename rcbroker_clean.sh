#!/bin/bash

rabbitmqctl delete_vhost it490

rabbitmqctl delete_user broker_admin
rabbitmqctl delete_user broker_user 
rabbitmqctl delete_user web_user 
rabbitmqctl delete_user db_user 
rabbitmqctl delete_user data_user 
