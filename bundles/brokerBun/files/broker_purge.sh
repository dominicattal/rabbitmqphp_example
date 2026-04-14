#!/bin/bash

rabbitmqctl purge_queue db_data_queue --vhost it490
rabbitmqctl purge_queue db_web_queue --vhost it490
rabbitmqctl purge_queue web_queue --vhost it490
rabbitmqctl purge_queue data_queue --vhost it490
