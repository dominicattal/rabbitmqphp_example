#!/bin/bash

# copies your ssh keys to each machine to allow scp

key_files=$(find ~/.ssh -type f -name "*.pub")
if [ -z "${key_files}" ]; then
    echo "Key not found, making one now"
    ssh-keygen -b 4096 -t rsa
    ssh-add
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
    echo "type is incorrect, should be deploy/ssh_copy.sh [deploy/web/db/data/all] [dev/qa/prod/all]"
    exit 1
fi

if [ "$type" != "deploy" ]; then
    target=$2
    if [ "$target" != "dev" ] && [ "$target" != "qa" ] && [ "$target" != "prod" ] && [ "$target" != "all" ]; then
        echo "target is incorrect, should be deploy/ssh_copy.sh [deploy/web/db/data/all] [dev/qa/prod/all]"
        exit 1
    fi
fi

if [ $type == "all" ] || [ $type == "deploy" ]; then
    if [ -n "${DEPLOY_HOST}" ] && [ -n "${DEPLOY_USER}" ]; then
        echo "-------- SSH COPY TO DEPLOY_HOST ---------"
        ssh_string="$DEPLOY_USER@$DEPLOY_HOST"
        echo $ssh_string
            echo $ssh_string
        ssh-copy-id $ssh_string
    else
        echo "DEPLOY_HOST or DEPLOY_USER not in clusters.ini"
    fi
fi

if [ $type == "all" ] || [ $type == "web" ]; then
    if [ $target == "all" ] || [ $target == "dev" ]; then 
        if [ -n "${DEV_WEB_HOST}" ] && [ -n "${DEV_WEB_USER}" ]; then
            echo "-------- SSH COPY TO DEV_WEB_HOST --------"
            ssh_string="$DEV_WEB_USER@$DEV_WEB_HOST"
            echo $ssh_string
            ssh-copy-id $ssh_string
        else
            echo "DEV_WEB_HOST or DEV_WEB_USER not in clusters.ini"
        fi
    fi
    if [ $target == "all" ] || [ $target == "qa" ]; then 
        if [ -n "${QA_WEB_HOST}" ] && [ -n "${QA_WEB_USER}" ]; then
            echo "-------- SSH COPY TO QA_WEB_HOST --------"
            ssh_string="$QA_WEB_USER@$QA_WEB_HOST"
            echo $ssh_string
            ssh-copy-id $ssh_string
        else
            echo "QA_WEB_HOST or QA_WEB_USER not in clusters.ini"
        fi
    fi
    if [ $target == "all" ] || [ $target == "prod" ]; then 
        if [ -n "${PROD_WEB_HOST}" ] && [ -n "${PROD_WEB_USER}" ]; then
            echo "-------- SSH COPY TO PROD_WEB_HOST --------"
            ssh_string="$PROD_WEB_USER@$PROD_WEB_HOST"
            echo $ssh_string
            ssh-copy-id $ssh_string
        else
            echo "PROD_WEB_HOST or PROD_WEB_USER not in clusters.ini"
        fi
    fi
fi

if [ $type == "all" ] || [ $type == "db" ]; then
    if [ $target == "all" ] || [ $target == "dev" ]; then 
        if [ -n "${DEV_DB_HOST}" ] && [ -n "${DEV_DB_USER}" ]; then
            echo "-------- SSH COPY TO DEV_DB_HOST --------"
            ssh_string="$DEV_DB_USER@$DEV_DB_HOST"
            echo $ssh_string
            ssh-copy-id $ssh_string
        else
            echo "DEV_DB_HOST or DEV_DB_USER not in clusters.ini"
        fi
    fi
    if [ $target == "all" ] || [ $target == "qa" ]; then 
        if [ -n "${QA_DB_HOST}" ] && [ -n "${QA_DB_USER}" ]; then
            echo "-------- SSH COPY TO QA_DB_HOST --------"
            ssh_string="$QA_DB_USER@$QA_DB_HOST"
            echo $ssh_string
            ssh-copy-id $ssh_string
        else
            echo "QA_DB_HOST or QA_DB_USER not in clusters.ini"
        fi
    fi
    if [ $target == "all" ] || [ $target == "prod" ]; then 
        if [ -n "${PROD_DB_HOST}" ] && [ -n "${PROD_DB_USER}" ]; then
            echo "-------- SSH COPY TO PROD_DB_HOST --------"
            ssh_string="$PROD_DB_USER@$PROD_DB_HOST"
            ssh-copy-id $ssh_string
        else
            echo "PROD_DB_HOST or PROD_DB_USER not in clusters.ini"
        fi
    fi
fi


if [ $type == "all" ] || [ $type == "data" ]; then
    if [ $target == "all" ] || [ $target == "dev" ]; then 
        if [ -n "${DEV_DATA_HOST}" ] && [ -n "${DEV_DATA_USER}" ]; then
            echo "-------- SSH COPY TO DEV_DATA_HOST --------"
            ssh_string="$DEV_DATA_USER@$DEV_DATA_HOST"
            echo $ssh_string
            ssh-copy-id $ssh_string
        else
            echo "DEV_DATA_HOST or DEV_DATA_USER not in clusters.ini"
        fi
    fi
    if [ $target == "all" ] || [ $target == "qa" ]; then 
        if [ -n "${QA_DATA_HOST}" ] && [ -n "${QA_DATA_USER}" ]; then
            echo "-------- SSH COPY TO QA_DATA_HOST --------"
            ssh_string="$QA_DATA_USER@$QA_DATA_HOST"
            echo $ssh_string
            ssh-copy-id $ssh_string
        else
            echo "QA_DATA_HOST or QA_DATA_USER not in clusters.ini"
        fi
    fi
    if [ $target == "all" ] || [ $target == "prod" ]; then 
        if [ -n "${PROD_DATA_HOST}" ] && [ -n "${PROD_DATA_USER}" ]; then
            echo "-------- SSH COPY TO PROD_DATA_HOST --------"
            ssh_string="$PROD_DATA_USER@$PROD_DATA_HOST"
            echo $ssh_string
            ssh-copy-id $ssh_string
        else
            echo "PROD_DATA_HOST or PROD_DATA_USER not in clusters.ini"
        fi
    fi
fi

