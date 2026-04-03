#!/bin/bash

# copies your ssh keys to each machine to allow scp

tail -n +2 "deploy/clusters.ini" > /tmp/clusters.sh
source /tmp/clusters.sh

if [ -n "${DEPLOY_HOST}" ] && [ -n "${DEPLOY_USER}" ]; then
    echo "-------- SSH COPY TO DEPLOY_HOST ---------"
    echo "$DEPLOY_USER@$DEPLOY_HOST"
    ssh-copy-id "$DEPLOY_USER@$DEPLOY_HOST"
fi

if [ -n "${DEV_WEB_HOST}" ] && [ -n "${DEV_WEB_USER}" ]; then
    echo "-------- SSH COPY TO DEV_WEB_HOST --------"
    echo "$DEV_WEB_USER@$DEV_WEB_HOST"
    ssh-copy-id "$DEV_WEB_USER@$DEV_WEB_HOST"
fi

if [ -n "${DEV_DB_HOST}" ] && [ -n "${DEV_DB_USER}" ]; then
    echo "-------- SSH COPY TO DEV_DB_HOST --------"
    echo "$DEV_DB_USER@$DEV_DB_HOST"
    ssh-copy-id "$DEV_DB_USER@$DEV_DB_HOST"
fi

if [ -n "${DEV_DATA_HOST}" ] && [ -n "${DEV_DATA_USER}" ]; then
    echo "-------- SSH COPY TO DEV_DATA_HOST --------"
    echo "$DEV_DATA_USER@$DEV_DATA_HOST"
    ssh-copy-id "$DEV_DATA_USER@$DEV_DATA_HOST"
fi

if [ -n "${QA_WEB_HOST}" ] && [ -n "${QA_WEB_USER}" ]; then
    echo "-------- SSH COPY TO QA_WEB_HOST --------"
    echo "$QA_WEB_USER@$QA_WEB_HOST"
    ssh-copy-id "$QA_WEB_USER@$QA_WEB_HOST"
fi

if [ -n "${QA_DB_HOST}" ] && [ -n "${QA_DB_USER}" ]; then
    echo "-------- SSH COPY TO QA_DB_HOST --------"
    echo "$QA_DB_USER@$QA_DB_HOST"
    ssh-copy-id "$QA_DB_USER@$QA_DB_HOST"
fi

if [ -n "${QA_DATA_HOST}" ] && [ -n "${QA_DATA_USER}" ]; then
    echo "-------- SSH COPY TO QA_DATA_HOST --------"
    echo "$QA_DATA_USER@$QA_DATA_HOST"
    ssh-copy-id "$QA_DATA_USER@$QA_DATA_HOST"
fi

if [ -n "${PROD_WEB_HOST}" ] && [ -n "${PROD_WEB_USER}" ]; then
    echo "-------- SSH COPY TO PROD_WEB_HOST --------"
    echo "$PROD_WEB_USER@$PROD_WEB_HOST"
    ssh-copy-id "$PROD_WEB_USER@$PROD_WEB_HOST"
fi

if [ -n "${PROD_DB_HOST}" ] && [ -n "${PROD_DB_USER}" ]; then
    echo "-------- SSH COPY TO PROD_DB_HOST --------"
    echo "$PROD_DB_USER@$PROD_DB_HOST"
    ssh-copy-id "$PROD_DB_USER@$PROD_DB_HOST"
fi

if [ -n "${PROD_DATA_HOST}" ] && [ -n "${PROD_DATA_USER}" ]; then
    echo "-------- SSH COPY TO PROD_DATA_HOST --------"
    echo "$PROD_DATA_USER@$PROD_DATA_HOST"
    ssh-copy-id "$PROD_DATA_USER@$PROD_DATA_HOST"
fi
