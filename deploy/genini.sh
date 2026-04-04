#!/bin/bash

if [ $# -eq 0 ]; then
    echo "Generate ini file for cluster"
    echo "Usage: deploy/genini.sh [main/deploy/dev/qa/prod/all] [web/db/data/all]"
    exit 1
fi

if [ ! -f "deploy/clusters.ini" ]; then
    echo "Missing deploy/clusters.ini"
    exit 1
fi

tail -n +2 "deploy/clusters.ini" > /tmp/clusters.sh
source /tmp/clusters.sh

target=$1
if [ "$target" != "main" ] && [ "$target" != "deploy" ] && [ "$target" != "dev" ] && [ "$target" != "qa" ] && [ "$target" != "prod" ] && [ "$target" != "all" ]; then
        echo "target is incorrect, should be [main/deploy/dev/qa/prod/all]"
        exit 1
fi

if [ "$target" != "main" ] && [ "$target" != "deploy" ]; then
    type=$2
    if [ "$type" != "web" ] && [ "$type" != "db" ] && [ "$type" != "data" ] && [ "$type" != "all" ]; then
        echo "type is incorrect, should be [web/db/data/all] "
        exit 1
    fi
fi

if [ -z ${DEPLOY_HOST} ]; then 
    echo "Missing DEPLOY_HOST field in deploy/clusters.ini"
    exit 1
fi

if [ "$target" == "all" ] || [ "$target" == "main" ]; then
    echo "Creating main_client.ini"
    path="./main_client.ini"
    sed -e "s/hostname/$DEPLOY_HOST/" -e "s/target/main/g" -e "s/type_//g" -e "s/LISTEN/REPLY/" -e "s/_listen//" -e "s/_server/_client/" deploy/template_server.ini > $path
    sed -i "2i DEPLOY_USER=$DEPLOY_USER" $path
    sed -i "2i DEPLOY_HOST=$DEPLOY_HOST" $path
fi
if [ "$target" == "all" ] || [ "$target" == "deploy" ]; then
    if [ -z ${DEPLOY_USER} ] || [ -z ${DEPLOY_HOST} ]; then
        echo "Deploy host or user not in cluster.ini"
    else
        echo "Copying inis for DEPLOY_HOST"
        ssh_string="$DEPLOY_USER@$DEPLOY_HOST"
        path="/tmp/deploy_server.ini"
        sed -e "s/hostname/localhost/" -e "s/target/deploy/g" -e "s/type_//g" deploy/template_server.ini > $path
        ssh $ssh_string "mkdir -p ~/it490"
        scp "$path" "scp://$ssh_string/~/it490/"
        path="/tmp/deploy_client.ini"
        sed -e "s/hostname/localhost/" -e "s/target/deploy/g" -e "s/type_//g" -e "s/LISTEN/REPLY/" -e "s/_listen//" -e "s/_server/_client/" deploy/template_server.ini > $path
        scp "$path" "scp://$ssh_string/~/it490/"
    fi
fi
if [ "$type" == "all" ] || [ "$type" == "web" ]; then
    if [ "$target" == "all" ] || [ "$target" == "dev" ]; then
        if [ -z ${DEV_WEB_USER} ] || [ -z ${DEV_WEB_HOST} ]; then
            echo "DEV_WEB_USER or DEV_WEB_HOST not in cluster.ini"
        else
            echo "Copying inis for DEV_WEB_HOST"
            ssh_string="$DEV_WEB_USER@$DEV_WEB_HOST"
            path="/tmp/cluster_server.ini"
            sed -e "s/hostname/$DEPLOY_HOST/" -e "s/target/dev/g" -e "s/type/web/g" deploy/template_server.ini > $path
            ssh $ssh_string "mkdir -p ~/it490"
            scp "$path" "scp://$ssh_string/~/it490/"
            path="/tmp/web_client.ini"
            sed -e "s/hostname/$DEV_DB_HOST/" deploy/template_web_client.ini > $path
            scp "$path" "scp://$ssh_string/~/it490/"
        fi
    fi
    if [ "$target" == "all" ] || [ "$target" == "qa" ]; then
        if [ -z ${QA_WEB_USER} ] || [ -z ${QA_WEB_HOST} ]; then
            echo "QA_WEB_USER or QA_WEB_HOST not in cluster.ini"
        else
            echo "Copying inis for QA_WEB_HOST"
            ssh_string="$QA_WEB_USER@$QA_WEB_HOST"
            path="/tmp/cluster_server.ini"
            sed -e "s/hostname/$DEPLOY_HOST/" -e "s/target/dev/g" -e "s/type/web/g" deploy/template_server.ini > $path
            ssh $ssh_string "mkdir -p ~/it490"
            scp "$path" "scp://$ssh_string/~/it490/"
            path="/tmp/web_client.ini"
            sed -e "s/hostname/$QA_DB_HOST/" deploy/template_web_client.ini > $path
            scp "$path" "scp://$ssh_string/~/it490/"
        fi
    fi
    if [ "$target" == "all" ] || [ "$target" == "prod" ]; then
        if [ -z ${PROD_WEB_USER} ] || [ -z ${PROD_WEB_HOST} ]; then
            echo "PROD_WEB_USER or PROD_WEB_HOST not in cluster.ini"
        else
            echo "Copying inis for PROD_WEB_HOST"
            ssh_string="$PROD_WEB_USER@$PROD_WEB_HOST"
            path="/tmp/cluster_server.ini"
            sed -e "s/hostname/$DEPLOY_HOST/" -e "s/target/dev/g" -e "s/type/web/g" deploy/template_server.ini > $path
            ssh $ssh_string "mkdir -p ~/it490"
            scp "$path" "scp://$ssh_string/~/it490/"
            path="/tmp/web_client.ini"
            sed -e "s/hostname/$PROD_DB_HOST/" deploy/template_web_client.ini > $path
            scp "$path" "scp://$ssh_string/~/it490/"
        fi
    fi
fi
if [ "$type" == "all" ] || [ "$type" == "db" ]; then
    if [ "$target" == "all" ] || [ "$target" == "dev" ]; then
        if [ -z ${DEV_DB_USER} ] || [ -z ${DEV_DB_HOST} ]; then
            echo "DEV_DB_USER or DEV_DB_HOST not in cluster.ini"
        else
            echo "Copying inis for DEV_DB_HOST"
            ssh_string="$DEV_DB_USER@$DEV_DB_HOST"
            path="/tmp/cluster_server.ini"
            sed -e "s/hostname/$DEPLOY_HOST/" -e "s/target/dev/g" -e "s/type/web/g" deploy/template_server.ini > $path
            ssh $ssh_string "mkdir -p ~/it490"
            scp "$path" "scp://$ssh_string/~/it490/"
            path="/tmp/db_client.ini"
            sed -e "s/hostname/$DEV_DB_HOST/" deploy/template_db_client.ini > $path
            scp "$path" "scp://$ssh_string/~/it490/"
            path="/tmp/db_server.ini"
            sed -e "s/hostname/$DEV_DB_HOST/" deploy/template_db_server.ini > $path
            scp "$path" "scp://$ssh_string/~/it490/"
            scp "db_mysql.ini" "scp://$ssh_string/~/it490/"
        fi
    fi
    if [ "$target" == "all" ] || [ "$target" == "qa" ]; then
        if [ -z ${QA_DB_USER} ] || [ -z ${QA_DB_HOST} ]; then
            echo "QA_DB_USER or QA_DB_HOST not in cluster.ini"
        else
            echo "Copying inis for QA_DB_HOST"
            ssh_string="$QA_DB_USER@$QA_DB_HOST"
            path="/tmp/cluster_server.ini"
            sed -e "s/hostname/$DEPLOY_HOST/" -e "s/target/dev/g" -e "s/type/web/g" deploy/template_server.ini > $path
            ssh $ssh_string "mkdir -p ~/it490"
            scp "$path" "scp://$ssh_string/~/it490/"
            path="/tmp/db_client.ini"
            sed -e "s/hostname/$QA_DB_HOST/" deploy/template_db_client.ini > $path
            scp "$path" "scp://$ssh_string/~/it490/"
            path="/tmp/db_server.ini"
            sed -e "s/hostname/$QA_DB_HOST/" deploy/template_db_server.ini > $path
            scp "$path" "scp://$ssh_string/~/it490/"
            scp "db_mysql.ini" "scp://$ssh_string/~/it490/"
        fi
    fi
    if [ "$target" == "all" ] || [ "$target" == "prod" ]; then
        if [ -z ${PROD_DB_USER} ] || [ -z ${PROD_DB_HOST} ]; then
            echo "PROD_DB_USER or PROD_DB_HOST not in cluster.ini"
        else
            echo "Copying inis for PROD_DB_HOST"
            ssh_string="$PROD_DB_USER@$PROD_DB_HOST"
            path="/tmp/cluster_server.ini"
            sed -e "s/hostname/$DEPLOY_HOST/" -e "s/target/dev/g" -e "s/type/web/g" deploy/template_server.ini > $path
            ssh $ssh_string "mkdir -p ~/it490"
            scp "$path" "scp://$ssh_string/~/it490/"
            path="/tmp/db_client.ini"
            sed -e "s/hostname/$PROD_DB_HOST/" deploy/template_db_client.ini > $path
            scp "$path" "scp://$ssh_string/~/it490/"
            path="/tmp/db_server.ini"
            sed -e "s/hostname/$PROD_DB_HOST/" deploy/template_db_server.ini > $path
            scp "$path" "scp://$ssh_string/~/it490/"
            scp "db_mysql.ini" "scp://$ssh_string/~/it490/"
        fi
    fi
fi
if [ "$type" == "all" ] || [ "$type" == "data" ]; then
    if [ "$target" == "all" ] || [ "$target" == "dev" ]; then
        if [ -z ${DEV_DATA_USER} ] || [ -z ${DEV_DATA_HOST} ]; then
            echo "DEV_DATA_USER or DEV_DATA_HOST not in cluster.ini"
        else
            echo "Copying inis for DEV_DATA_HOST"
            ssh_string="$DEV_DATA_USER@$DEV_DATA_HOST"
            path="/tmp/cluster_server.ini"
            sed -e "s/hostname/$DEPLOY_HOST/" -e "s/target/dev/g" -e "s/type/web/g" deploy/template_server.ini > $path
            ssh $ssh_string "mkdir -p ~/it490"
            scp "$path" "scp://$ssh_string/~/it490/"
            path="/tmp/data_server.ini"
            sed -e "s/hostname/$DEV_DB_HOST/" deploy/template_data_server.ini > $path
            scp "$path" "scp://$ssh_string/~/it490/"
        fi
    fi
    if [ "$target" == "all" ] || [ "$target" == "qa" ]; then
        if [ -z ${QA_DATA_USER} ] || [ -z ${QA_DATA_HOST} ]; then
            echo "QA_DATA_USER or QA_DATA_HOST not in cluster.ini"
        else
            echo "Copying inis for QA_DATA_HOST"
            ssh_string="$QA_DATA_USER@$QA_DATA_HOST"
            path="/tmp/cluster_server.ini"
            sed -e "s/hostname/$DEPLOY_HOST/" -e "s/target/dev/g" -e "s/type/web/g" deploy/template_server.ini > $path
            ssh $ssh_string "mkdir -p ~/it490"
            scp "$path" "scp://$ssh_string/~/it490/"
            path="/tmp/data_server.ini"
            sed -e "s/hostname/$QA_DB_HOST/" deploy/template_data_server.ini > $path
            scp "$path" "scp://$ssh_string/~/it490/"
        fi
    fi
    if [ "$target" == "all" ] || [ "$target" == "prod" ]; then
        if [ -z ${PROD_DATA_USER} ] || [ -z ${PROD_DATA_HOST} ]; then
            echo "PROD_DATA_USER or PROD_DATA_HOST not in cluster.ini"
        else
            echo "Copying inis for PROD_DATA_HOST"
            ssh_string="$PROD_DATA_USER@$PROD_DATA_HOST"
            path="/tmp/cluster_server.ini"
            sed -e "s/hostname/$DEPLOY_HOST/" -e "s/target/dev/g" -e "s/type/web/g" deploy/template_server.ini > $path
            ssh $ssh_string "mkdir -p ~/it490"
            scp "$path" "scp://$ssh_string/~/it490/"
            path="/tmp/data_server.ini"
            sed -e "s/hostname/$PROD_DB_HOST/" deploy/template_data_server.ini > $path
            scp "$path" "scp://$ssh_string/~/it490/"
        fi
    fi
fi
