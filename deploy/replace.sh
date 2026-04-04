#!/bin/bash

if [ -z "deploy/clusters.ini" ]; then
    echo "Missing clusters file"
    exit 1
fi

deploy_field=$(grep "DEPLOY_HOST *= *[0-9.]*" deploy/clusters.ini)
if [ -z ${deploy_field} ]; then
    echo "Missing DEPLOY_HOST field in deploy/clusters.ini"
    exit 1
fi
deploy_host=$(echo $deploy_field | grep -o "[0-9.]*" )
if [ -z ${deploy_host} ]; then
    echo "DEPLOY_HOST field is empty"
    exit 1
fi

find deploy/serverinis -type f -exec sed -i -e "s/MQ_HOST = .*/MQ_HOST = $deploy_host/" {} +
echo "Successfully updated hosts"

