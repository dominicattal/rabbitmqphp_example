#!/bin/bash

# this script copies file to specified machines. not to be confused wiht deployment system, this is just for setting stuff up easily

# -------- FILES TO COPY --------------
deploy_files="deploy/ssh_copy.sh deploy/apt.sh deploy/broker.sh deploy/clusters.ini deploy/deploy_client.ini deploy/deploy_server.ini deploy/deploy.php ./rabbitMQLib.inc"

cluster_files="deploy/handler.php deploy/apt.sh ./rabbitMQLib.inc"

web_files="$cluster_files"
db_files="$cluster_files"
data_files="$cluster_files"

dev_web_files="$web_files deploy/dev_web_server.ini"
dev_db_files="$db_files deploy/dev_db_server.ini"
dev_data_files="$data_files deploy/dev_data_server.ini"

qa_web_files="$web_files deploy/qa_web_server.ini"
qa_db_files="$db_files deploy/qa_db_server.ini"
qa_data_files="$data_files deploy/qa_data_server.ini"

data_web_files="$web_files deploy/data_web_server.ini"
data_db_files="$db_files deploy/data_db_server.ini"
data_data_files="$data_files deploy/data_data_server.ini"
# ---------------------------------------

if [ $# -eq 0 ]; then
    echo "Update core files onto each machine"
    echo "run deploy/ssh_copy.sh to update ssh keys"
    echo "Usage: deploy/update.sh [deploy/web/db/data/all] [dev/qa/prod/all]"
    echo "Files can be specified after type and target. Otherwise, it will copy the ones specified above"
    exit 1
fi


type=$1
if [ "$type" != "deploy" ] && [ "$type" != "web" ] && [ "$type" != "db" ] && [ "$type" != "data" ] && [ "$type" != "all" ]; then
    echo "type is incorrect, should be [deploy/web/db/data/all] "
    exit 1
fi

if [ "$type" != "deploy" ]; then
    target=$2
    if [ "$target" != "dev" ] && [ "$target" != "qa" ] && [ "$target" != "prod" ] && [ "$target" != "all" ]; then
        echo "target is incorrect, should be [dev/qa/prod/all]"
        exit 1
    fi
    cli_files=${@:3}
else
    cli_files=${@:2}
fi

if [ ! -z "${cli_files}" ]; then
    echo "Copying files $cli_files"
    deploy_files="$cli_files"
    web_files="$cli_files"
    dev_web_files="$cli_files"
    dev_db_files="$cli_files"
    dev_data_files="$cli_files"
    db_files="$cli_files"
    qa_web_files="$cli_files"
    qa_db_files="$cli_files"
    qa_data_files="$cli_files"
    data_files="$cli_files"
    prod_web_files="$cli_files"
    prod_db_files="$cli_files"
    prod_data_files="$cli_files"
fi

if [ -f "deploy/clusters.ini" ]; then
    tail -n +2 "deploy/clusters.ini" > /tmp/clusters.sh
    source /tmp/clusters.sh
elif [ -f "clusters.ini" ]; then
    tail -n +2 "clusters.ini" > /tmp/clusters.sh
    source /tmp/clusters.sh
else
    echo "Could not find clusters.ini"
    exit 1
fi

if [ "$type" == "deploy" ]; then
    if [ -z ${DEPLOY_USER} ] || [ -z ${DEPLOY_HOST} ]; then
        echo "Deploy host or user not in cluster.ini"
    else
        ssh_string="$DEPLOY_USER@$DEPLOY_HOST"
        echo "Copying to DEPLOY_HOST $ssh_string"
        for file in $deploy_files; do
            if [ -f "$file" ]; then
                scp "$file" "scp://$ssh_string/~/it490//"
            else
                echo "$file does not exist, skipping"
            fi
        done
    fi
fi
if [ "$type" == "all" ] || [ "$type" == "web" ]; then
    if [ "$target" == "all" ] || [ "$target" == "dev" ]; then
        if [ -z ${DEV_WEB_USER} ] || [ -z ${DEV_WEB_HOST} ]; then
            echo "DEV_WEB_USER or DEV_WEB_HOST not in cluster.ini"
        else
            ssh_string="$DEV_WEB_USER@$DEV_WEB_HOST"
            echo "Copying to DEV_WEB_HOST $ssh_string"
            for file in $dev_web_files; do
                if [ -f "$file" ]; then
                    scp "$file" "scp://$ssh_string/~/it490/"
                else
                    echo "$file does not exist, skipping"
                fi
            done
        fi
    fi
    if [ "$target" == "all" ] || [ "$target" == "qa" ]; then
        if [ -z ${QA_WEB_USER} ] || [ -z ${QA_WEB_HOST} ]; then
            echo "QA_WEB_USER or QA_WEB_HOST not in cluster.ini"
        else
            ssh_string="$QA_WEB_USER@$QA_WEB_HOST"
            echo "Copying to QA_WEB_HOST $ssh_string"
            for file in $qa_web_files; do
                if [ -f "$file" ]; then
                    scp "$file" "scp://$ssh_string/~/it490/"
                else
                    echo "$file does not exist, skipping"
                fi
            done
        fi
    fi
    if [ "$target" == "all" ] || [ "$target" == "prod" ]; then
        if [ -z ${PROD_WEB_USER} ] || [ -z ${PROD_WEB_HOST} ]; then
            echo "PROD_WEB_USER or PROD_WEB_HOST not in cluster.ini"
        else
            ssh_string="$PROD_WEB_USER@$PROD_WEB_HOST"
            echo "Copying to PROD_WEB_HOST $ssh_string"
            for file in $prod_web_files; do
                if [ -f "$file" ]; then
                    scp "$file" "scp://$ssh_string/~/it490/"
                else
                    echo "$file does not exist, skipping"
                fi
            done
        fi
    fi
fi
if [ "$type" == "all" ] || [ "$type" == "db" ]; then
    if [ "$target" == "all" ] || [ "$target" == "dev" ]; then
        if [ -z ${DEV_DB_USER} ] || [ -z ${DEV_DB_HOST} ]; then
            echo "DEV_DB_USER or DEV_DB_HOST not in cluster.ini"
        else
            ssh_string="$DEV_DB_USER@$DEV_DB_HOST"
            echo "Copying to DEV_DB_HOST $ssh_string"
            for file in $dev_db_files; do
                if [ -f "$file" ]; then
                    scp "$file" "scp://$ssh_string/~/it490/"
                else
                    echo "$file does not exist, skipping"
                fi
            done
        fi
    fi
    if [ "$target" == "all" ] || [ "$target" == "qa" ]; then
        if [ -z ${QA_DB_USER} ] || [ -z ${QA_DB_HOST} ]; then
            echo "QA_DB_USER or QA_DB_HOST not in cluster.ini"
        else
            ssh_string="$QA_DB_USER@$QA_DB_HOST"
            echo "Copying to QA_DB_HOST $ssh_string"
            for file in $qa_db_files; do
                if [ -f "$file" ]; then
                    scp "$file" "scp://$ssh_string/~/it490/"
                else
                    echo "$file does not exist, skipping"
                fi
            done
        fi
    fi
    if [ "$target" == "all" ] || [ "$target" == "prod" ]; then
        if [ -z ${PROD_DB_USER} ] || [ -z ${PROD_DB_HOST} ]; then
            echo "PROD_DB_USER or PROD_DB_HOST not in cluster.ini"
        else
            ssh_string="$PROD_DB_USER@$PROD_DB_HOST"
            echo "Copying to PROD_DB_HOST $ssh_string"
            for file in $prod_db_files; do
                if [ -f "$file" ]; then
                    scp "$file" "scp://$ssh_string/~/it490/"
                else
                    echo "$file does not exist, skipping"
                fi
            done
        fi
    fi
fi
if [ "$type" == "all" ] || [ "$type" == "data" ]; then
    if [ "$target" == "all" ] || [ "$target" == "dev" ]; then
        if [ -z ${DEV_DATA_USER} ] || [ -z ${DEV_DATA_HOST} ]; then
            echo "DEV_DATA_USER or DEV_DATA_HOST not in cluster.ini"
        else
            ssh_string="$DEV_DATA_USER@$DEV_DATA_HOST"
            echo "Copying to DEV_DATA_HOST $ssh_string"
            for file in $dev_data_files; do
                if [ -f "$file" ]; then
                    scp "$file" "scp://$ssh_string/~/it490/"
                else
                    echo "$file does not exist, skipping"
                fi
            done
        fi
    fi
    if [ "$target" == "all" ] || [ "$target" == "qa" ]; then
        if [ -z ${QA_DATA_USER} ] || [ -z ${QA_DATA_HOST} ]; then
            echo "QA_DATA_USER or QA_DATA_HOST not in cluster.ini"
        else
            ssh_string="$QA_DATA_USER@$QA_DATA_HOST"
            echo "Copying to QA_DATA_HOST $ssh_string"
            for file in $qa_data_files; do
                if [ -f "$file" ]; then
                    scp "$file" "scp://$ssh_string/~/it490/"
                else
                    echo "$file does not exist, skipping"
                fi
            done
        fi
    fi
    if [ "$target" == "all" ] || [ "$target" == "prod" ]; then
        if [ -z ${PROD_DATA_USER} ] || [ -z ${PROD_DATA_HOST} ]; then
            echo "PROD_DATA_USER or PROD_DATA_HOST not in cluster.ini"
        else
            ssh_string="$PROD_DATA_USER@$PROD_DATA_HOST"
            echo "Copying to PROD_DATA_HOST $ssh_string"
            for file in $prod_data_files; do
                if [ -f "$file" ]; then
                    scp "$file" "scp://$ssh_string/~/it490/"
                else
                    echo "$file does not exist, skipping"
                fi
            done
        fi
    fi
fi
