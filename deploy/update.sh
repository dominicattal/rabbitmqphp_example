#!/bin/bash

if [ $# -eq 0 ]; then 
    echo "Usage: ./setup_deploy.sh user@host"
    echo "Call from project directory"
    exit 1
fi

scp deploy/broker.sh "scp://$1/~"
scp deploy/clusters.ini "scp://$1/~"
scp deploy/deploy_server.ini "scp://$1/~"
scp deploy/deploy.php "scp://$1/~"
scp ./rabbitMQLib.inc "scp://$1/~"
