#!/bin/bash

# this script copies file to target machines. not to be confused wiht deployment system, this is just for setting stuff up easily

# -------- FILES TO COPY --------------
deploy_files="deploy/broker.sh deploy/clusters.ini deploy/deploy_server.ini deploy/deploy.php ./rabbitMQLib.inc"
web_files=""
db_files=""
data_files=""
# ---------------------------------------

if [ $# -ne 1 ]; then
    echo "Update core files onto each machine"
    echo "Usage: update.sh [deploy/web/db/data/all]"
    exit 1
fi

tail -n +2 "deploy/clusters.ini" > /tmp/clusters.sh
source /tmp/clusters.sh

if [ $1 == "all" ] || [ $1 == "deploy" ]; then
    if [ -z ${DEPLOY_USER} ] || [ -z ${DEPLOY_HOST} ]; then
        echo "Deploy host or user not in cluster.ini"
    else
        ssh_string="$DEPLOY_USER@$DEPLOY_HOST"
        echo "Copying to DEPLOY_HOST $ssh_string"
        for file in $deploy_files; do
            if [ -f "$file" ]; then
                scp "$file" "scp://$ssh_string/~"
            else
                echo "$file does not exist, skipping"
            fi
        done
    fi
fi
if [ $1 == "all" ] || [ $1 == "web" ]; then
    if [ -z ${DEV_WEB_USER} ] || [ -z ${DEV_WEB_HOST} ]; then
        echo "DEV_WEB_USER or DEV_WEB_HOST not in cluster.ini"
    else
        ssh_string="$DEV_WEB_USER@$DEV_WEB_HOST"
        echo "Copying to DEV_WEB_HOST $ssh_string"
        for file in $web_files; do
            if [ -f "$file" ]; then
                scp "$file" "scp://$ssh_string/~"
            else
                echo "$file does not exist, skipping"
            fi
        done
    fi
    if [ -z ${QA_WEB_USER} ] || [ -z ${QA_WEB_HOST} ]; then
        echo "QA_WEB_USER or QA_WEB_HOST not in cluster.ini"
    else
        ssh_string="$QA_WEB_USER@$QA_WEB_HOST"
        echo "Copying to QA_WEB_HOST $ssh_string"
        for file in $web_files; do
            if [ -f "$file" ]; then
                scp "$file" "scp://$ssh_string/~"
            else
                echo "$file does not exist, skipping"
            fi
        done
    fi
    if [ -z ${PROD_WEB_USER} ] || [ -z ${PROD_WEB_HOST} ]; then
        echo "PROD_WEB_USER or PROD_WEB_HOST not in cluster.ini"
    else
        ssh_string="$PROD_WEB_USER@$PROD_WEB_HOST"
        echo "Copying to PROD_WEB_HOST $ssh_string"
        for file in $web_files; do
            if [ -f "$file" ]; then
                scp "$file" "scp://$ssh_string/~"
            else
                echo "$file does not exist, skipping"
            fi
        done
    fi
fi
if [ $1 == "all" ] || [ $1 == "db" ]; then
    if [ -z ${DEV_DB_USER} ] || [ -z ${DEV_DB_HOST} ]; then
        echo "DEV_DB_USER or DEV_DB_HOST not in cluster.ini"
    else
        ssh_string="$DEV_DB_USER@$DEV_DB_HOST"
        echo "Copying to DEV_DB_HOST $ssh_string"
        for file in $db_files; do
            if [ -f "$file" ]; then
                scp "$file" "scp://$ssh_string/~"
            else
                echo "$file does not exist, skipping"
            fi
        done
    fi
    if [ -z ${QA_DB_USER} ] || [ -z ${QA_DB_HOST} ]; then
        echo "QA_DB_USER or QA_DB_HOST not in cluster.ini"
    else
        ssh_string="$QA_DB_USER@$QA_DB_HOST"
        echo "Copying to QA_DB_HOST $ssh_string"
        for file in $db_files; do
            if [ -f "$file" ]; then
                scp "$file" "scp://$ssh_string/~"
            else
                echo "$file does not exist, skipping"
            fi
        done
    fi
    if [ -z ${PROD_DB_USER} ] || [ -z ${PROD_DB_HOST} ]; then
        echo "PROD_DB_USER or PROD_DB_HOST not in cluster.ini"
    else
        ssh_string="$PROD_DB_USER@$PROD_DB_HOST"
        echo "Copying to PROD_DB_HOST $ssh_string"
        for file in $db_files; do
            if [ -f "$file" ]; then
                scp "$file" "scp://$ssh_string/~"
            else
                echo "$file does not exist, skipping"
            fi
        done
    fi
fi
if [ $1 == "all" ] || [ $1 == "data" ]; then
    if [ -z ${DEV_DATA_USER} ] || [ -z ${DEV_DATA_HOST} ]; then
        echo "DEV_DATA_USER or DEV_DATA_HOST not in cluster.ini"
    else
        ssh_string="$DEV_DATA_USER@$DEV_DATA_HOST"
        echo "Copying to DEV_DATA_HOST $ssh_string"
        for file in $data_files; do
            if [ -f "$file" ]; then
                scp "$file" "scp://$ssh_string/~"
            else
                echo "$file does not exist, skipping"
            fi
        done
    fi
    if [ -z ${QA_DATA_USER} ] || [ -z ${QA_DATA_HOST} ]; then
        echo "QA_DATA_USER or QA_DATA_HOST not in cluster.ini"
    else
        ssh_string="$QA_DATA_USER@$QA_DATA_HOST"
        echo "Copying to QA_DATA_HOST $ssh_string"
        for file in $data_files; do
            if [ -f "$file" ]; then
                scp "$file" "scp://$ssh_string/~"
            else
                echo "$file does not exist, skipping"
            fi
        done
    fi
    if [ -z ${PROD_DATA_USER} ] || [ -z ${PROD_DATA_HOST} ]; then
        echo "PROD_DATA_USER or PROD_DATA_HOST not in cluster.ini"
    else
        ssh_string="$PROD_DATA_USER@$PROD_DATA_HOST"
        echo "Copying to PROD_DATA_HOST $ssh_string"
        for file in $data_files; do
            if [ -f "$file" ]; then
                scp "$file" "scp://$ssh_string/~"
            else
                echo "$file does not exist, skipping"
            fi
        done
    fi
fi
