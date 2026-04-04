#!/bin/bash

# this script removes everything from it490 directory for specified machine

if [ $# -eq 0 ]; then
    echo "Remove it490 directory of machines"
    echo "run deploy/ssh_copy.sh to update ssh keys"
    echo "Usage: deploy/clear.sh [deploy/web/db/data/all] [dev/qa/prod/all]"
    exit 1
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
fi

tail -n +2 "deploy/clusters.ini" > /tmp/clusters.sh
source /tmp/clusters.sh

if [ "$type" == "all" ] || [ "$type" == "deploy" ]; then
    if [ -z ${DEPLOY_USER} ] || [ -z ${DEPLOY_HOST} ]; then
        echo "Deploy host or user not in cluster.ini"
    else
        ssh_string="$DEPLOY_USER@$DEPLOY_HOST"
        printf "Removing files from DEPLOY_HOST $ssh_string ... "
        ssh "$ssh_string" "rm -r ~/it490"
        if [ $? -eq 0 ]; then
            echo "Success"
        fi
    fi
fi
if [ "$type" == "all" ] || [ "$type" == "web" ]; then
    if [ "$target" == "all" ] || [ "$target" == "dev" ]; then
        if [ -z ${DEV_WEB_USER} ] || [ -z ${DEV_WEB_HOST} ]; then
            echo "DEV_WEB_USER or DEV_WEB_HOST not in cluster.ini"
        else
            ssh_string="$DEV_WEB_USER@$DEV_WEB_HOST"
            printf "Removing files from DEV_WEB_HOST $ssh_string ... "
            ssh "$ssh_string" "rm -r ~/it490"
            if [ $? -eq 0 ]; then
                echo "Success"
            fi
        fi
    fi
    if [ "$target" == "all" ] || [ "$target" == "qa" ]; then
        if [ -z ${QA_WEB_USER} ] || [ -z ${QA_WEB_HOST} ]; then
            echo "QA_WEB_USER or QA_WEB_HOST not in cluster.ini"
        else
            ssh_string="$QA_WEB_USER@$QA_WEB_HOST"
            printf "Removing files from QA_WEB_HOST $ssh_string ... "
            ssh "$ssh_string" "rm -r ~/it490"
            if [ $? -eq 0 ]; then
                echo "Success"
            fi
        fi
    fi
    if [ "$target" == "all" ] || [ "$target" == "prod" ]; then
        if [ -z ${PROD_WEB_USER} ] || [ -z ${PROD_WEB_HOST} ]; then
            echo "PROD_WEB_USER or PROD_WEB_HOST not in cluster.ini"
        else
            ssh_string="$PROD_WEB_USER@$PROD_WEB_HOST"
            printf "Removing files from PROD_WEB_HOST $ssh_string ... "
            ssh "$ssh_string" "rm -r ~/it490"
            if [ $? -eq 0 ]; then
                echo "Success"
            fi
        fi
    fi
fi
if [ "$type" == "all" ] || [ "$type" == "db" ]; then
    if [ "$target" == "all" ] || [ "$target" == "dev" ]; then
        if [ -z ${DEV_DB_USER} ] || [ -z ${DEV_DB_HOST} ]; then
            echo "DEV_DB_USER or DEV_DB_HOST not in cluster.ini"
        else
            ssh_string="$DEV_DB_USER@$DEV_DB_HOST"
            printf "Removing files from DEV_DB_HOST $ssh_string ... "
            ssh "$ssh_string" "rm -r ~/it490"
            if [ $? -eq 0 ]; then
                echo "Success"
            fi
        fi
    fi
    if [ "$target" == "all" ] || [ "$target" == "qa" ]; then
        if [ -z ${QA_DB_USER} ] || [ -z ${QA_DB_HOST} ]; then
            echo "QA_DB_USER or QA_DB_HOST not in cluster.ini"
        else
            ssh_string="$QA_DB_USER@$QA_DB_HOST"
            printf "Removing files from QA_DB_HOST $ssh_string ... "
            ssh "$ssh_string" "rm -r ~/it490"
            if [ $? -eq 0 ]; then
                echo "Success"
            fi
        fi
    fi
    if [ "$target" == "all" ] || [ "$target" == "prod" ]; then
        if [ -z ${PROD_DB_USER} ] || [ -z ${PROD_DB_HOST} ]; then
            echo "PROD_DB_USER or PROD_DB_HOST not in cluster.ini"
        else
            ssh_string="$PROD_DB_USER@$PROD_DB_HOST"
            printf "Removing files from PROD_DB_HOST $ssh_string ... "
            ssh "$ssh_string" "rm -r ~/it490"
            if [ $? -eq 0 ]; then
                echo "Success"
            fi
        fi
    fi
fi
if [ "$type" == "all" ] || [ "$type" == "data" ]; then
    if [ "$target" == "all" ] || [ "$target" == "dev" ]; then
        if [ -z ${DEV_DATA_USER} ] || [ -z ${DEV_DATA_HOST} ]; then
            echo "DEV_DATA_USER or DEV_DATA_HOST not in cluster.ini"
        else
            ssh_string="$DEV_DATA_USER@$DEV_DATA_HOST"
            printf "Removing files from DEV_DATA_HOST $ssh_string ... "
            ssh "$ssh_string" "rm -r ~/it490"
            if [ $? -eq 0 ]; then
                echo "Success"
            fi
        fi
    fi
    if [ "$target" == "all" ] || [ "$target" == "qa" ]; then
        if [ -z ${QA_DATA_USER} ] || [ -z ${QA_DATA_HOST} ]; then
            echo "QA_DATA_USER or QA_DATA_HOST not in cluster.ini"
        else
            ssh_string="$QA_DATA_USER@$QA_DATA_HOST"
            printf "Removing files from QA_DATA_HOST $ssh_string ... "
            ssh "$ssh_string" "rm -r ~/it490"
            if [ $? -eq 0 ]; then
                echo "Success"
            fi
        fi
    fi
    if [ "$target" == "all" ] || [ "$target" == "prod" ]; then
        if [ -z ${PROD_DATA_USER} ] || [ -z ${PROD_DATA_HOST} ]; then
            echo "PROD_DATA_USER or PROD_DATA_HOST not in cluster.ini"
        else
            ssh_string="$PROD_DATA_USER@$PROD_DATA_HOST"
            printf "Removing files from PROD_DATA_HOST $ssh_string ... "
            ssh "$ssh_string" "rm -r ~/it490"
            if [ $? -eq 0 ]; then
                echo "Success"
            fi
        fi
    fi
fi
