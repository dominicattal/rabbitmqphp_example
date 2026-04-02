#!/bin/bash

path="$(dirname ${BASH_SOURCE[0]})/.deploy_uri"
if [ $# -ge 1 ]; then 
    ssh_string=$1
    echo "Storing ssh string to $path"
    echo $ssh_string > $path
elif [ -f $path ]; then
    echo "Fetching ssh string from $path"
    ssh_string=`cat $path`
else
    echo "error: Call from project directory"
    echo "Usage: ./update.sh user@host"
    exit 1
fi

scp deploy/broker.sh "scp://$ssh_string/~"
scp deploy/clusters.ini "scp://$ssh_string/~"
scp deploy/deploy_server.ini "scp://$ssh_string/~"
scp deploy/deploy.php "scp://$ssh_string/~"
scp ./rabbitMQLib.inc "scp://$ssh_string/~"
