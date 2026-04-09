#!/bin/bash

if [ $# -eq 0 ]; then
    echo "Generate ini file for cluster"
    echo "Usage: deploy/genini.sh [deploy/dev/qa/prod/all] [web/db/data/all]"
    exit 1
fi

if [ ! -f "deploy/clusters.ini" ]; then
    echo "Missing deploy/clusters.ini"
    exit 1
fi

tail -n +2 "deploy/clusters.ini" > /tmp/clusters.sh
source /tmp/clusters.sh

target=$1
if [ "$target" != "deploy" ] && [ "$target" != "dev" ] && [ "$target" != "qa" ] && [ "$target" != "prod" ] && [ "$target" != "all" ]; then
        echo "target is incorrect, should be [deploy/dev/qa/prod/all]"
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

path="/tmp/ufw.sh"
if [ "$target" == "all" ] || [ "$target" == "deploy" ]; then
    if [ -z ${DEPLOY_USER} ] || [ -z ${DEPLOY_HOST} ]; then
        echo "Deploy host or user not in cluster.ini"
    else
        ssh_string="$DEPLOY_USER@$DEPLOY_HOST"
        echo "Generating ufw script for DEPLOY_HOST ($ssh_string)"
        echo '#!/bin/bash' > $path
        echo 'ufw reset' >> $path
        echo 'ufw default deny' >> $path
        if [ ! -z ${DEV_WEB_HOST} ]; then
            echo "ufw allow from ${DEV_WEB_HOST} proto tcp to any port 5672 # DEV_WEB_HOST" >> $path
        fi
        if [ ! -z ${DEV_DB_HOST} ]; then
            echo "ufw allow from ${DEV_DB_HOST} proto tcp to any port 5672 # DEV_DB_HOST" >> $path
        fi
        if [ ! -z ${DEV_DATA_HOST} ]; then
            echo "ufw allow from ${DEV_DATA_HOST} proto tcp to any port 5672 # DEV_DATA_HOST" >> $path
        fi
        if [ ! -z ${QA_WEB_HOST} ]; then
            echo "ufw allow from ${QA_WEB_HOST} proto tcp to any port 5672 # QA_WEB_HOST" >> $path
        fi
        if [ ! -z ${QA_DB_HOST} ]; then
            echo "ufw allow from ${QA_DB_HOST} proto tcp to any port 5672 # QA_DB_HOST" >> $path
        fi
        if [ ! -z ${QA_DATA_HOST} ]; then
            echo "ufw allow from ${QA_DATA_HOST} proto tcp to any port 5672 # QA_DATA_HOST" >> $path
        fi
        if [ ! -z ${PROD_WEB_HOST} ]; then
            echo "ufw allow from ${PROD_WEB_HOST} proto tcp to any port 5672 # PROD_WEB_HOST" >> $path
        fi
        if [ ! -z ${PROD_DB_HOST} ]; then
            echo "ufw allow from ${PROD_DB_HOST} proto tcp to any port 5672 # PROD_DB_HOST" >> $path
        fi
        if [ ! -z ${PROD_DATA_HOST} ]; then
            echo "ufw allow from ${PROD_DATA_HOST} proto tcp to any port 5672 # PROD_DATA_HOST" >> $path
        fi
        echo "ufw enable" >> $path
        ssh $ssh_string "mkdir -p ~/it490"
        scp "$path" "scp://$ssh_string/~/it490/"
    fi
fi
if [ "$type" == "all" ] || [ "$type" == "web" ]; then
    if [ "$target" == "all" ] || [ "$target" == "dev" ]; then
        if [ -z ${DEV_WEB_USER} ] || [ -z ${DEV_WEB_HOST} ]; then
            echo "DEV_WEB_USER or DEV_WEB_HOST not in cluster.ini"
        else
            ssh_string="$DEV_WEB_USER@$DEV_WEB_HOST"
            echo "Generating ufw scripts for DEV_WEB_HOST ($ssh_string)"
            echo "#!/bin/bash" > $path
            echo 'ufw reset' >> $path
            echo 'ufw default deny' >> $path
            echo "ufw allow from ${DEPLOY_HOST} proto tcp to any port 5672 # DEPLOY_HOST" >> $path
            if [ ! -z ${DEV_DB_HOST} ]; then
                echo "ufw allow from ${DEV_DB_HOST} proto tcp to any port 5672 # DEV_DB_HOST" >> $path
            fi
            echo "ufw enable" >> $path
            ssh $ssh_string "mkdir -p ~/it490"
            scp "$path" "scp://$ssh_string/~/it490/"
        fi
    fi
    if [ "$target" == "all" ] || [ "$target" == "qa" ]; then
        if [ -z ${QA_WEB_USER} ] || [ -z ${QA_WEB_HOST} ]; then
            echo "QA_WEB_USER or QA_WEB_HOST not in cluster.ini"
        else
            ssh_string="$QA_WEB_USER@$QA_WEB_HOST"
            echo "Generating ufw scripts for QA_WEB_HOST ($ssh_string)"
            echo "#!/bin/bash" > $path
            echo 'ufw reset' >> $path
            echo 'ufw default deny' >> $path
            echo "ufw allow from ${DEPLOY_HOST} proto tcp to any port 5672 # DEPLOY_HOST" >> $path
            if [ ! -z ${QA_DB_HOST} ]; then
                echo "ufw allow from ${QA_DB_HOST} proto tcp to any port 5672 # QA_DB_HOST" >> $path
            fi
            echo "ufw enable" >> $path
            ssh $ssh_string "mkdir -p ~/it490"
            scp "$path" "scp://$ssh_string/~/it490/"
        fi
    fi
    if [ "$target" == "all" ] || [ "$target" == "prod" ]; then
        if [ -z ${PROD_WEB_USER} ] || [ -z ${PROD_WEB_HOST} ]; then
            echo "PROD_WEB_USER or PROD_WEB_HOST not in cluster.ini"
        else
            ssh_string="$PROD_WEB_USER@$PROD_WEB_HOST"
            echo "Generating ufw scripts for PROD_WEB_HOST ($ssh_string)"
            echo "#!/bin/bash" > $path
            echo 'ufw reset' >> $path
            echo 'ufw default deny' >> $path
            echo "ufw allow from ${DEPLOY_HOST} proto tcp to any port 5672 # DEPLOY_HOST" >> $path
            if [ ! -z ${PROD_DB_HOST} ]; then
                echo "ufw allow from ${PROD_DB_HOST} proto tcp to any port 5672 # PROD_DB_HOST" >> $path
            fi
            echo "ufw enable" >> $path
            ssh $ssh_string "mkdir -p ~/it490"
            scp "$path" "scp://$ssh_string/~/it490/"
        fi
    fi
fi
if [ "$type" == "all" ] || [ "$type" == "db" ]; then
    if [ "$target" == "all" ] || [ "$target" == "dev" ]; then
        if [ -z ${DEV_DB_USER} ] || [ -z ${DEV_DB_HOST} ]; then
            echo "DEV_DB_USER or DEV_DB_HOST not in cluster.ini"
        else
            ssh_string="$DEV_DB_USER@$DEV_DB_HOST"
            echo "Generating ufw scripts for DEV_DB_HOST ($ssh_string)"
            echo "#!/bin/bash" > $path
            echo 'ufw reset' >> $path
            echo 'ufw default deny' >> $path
            echo "ufw allow from ${DEPLOY_HOST} proto tcp to any port 5672 # DEPLOY_HOST" >> $path
            echo "ufw enable" >> $path
            ssh $ssh_string "mkdir -p ~/it490"
            scp "$path" "scp://$ssh_string/~/it490/"
        fi
    fi
    if [ "$target" == "all" ] || [ "$target" == "qa" ]; then
        if [ -z ${QA_DB_USER} ] || [ -z ${QA_DB_HOST} ]; then
            echo "QA_DB_USER or QA_DB_HOST not in cluster.ini"
        else
            ssh_string="$QA_DB_USER@$QA_DB_HOST"
            echo "Generating ufw scripts for QA_DB_HOST ($ssh_string)"
            echo "#!/bin/bash" > $path
            echo 'ufw reset' >> $path
            echo 'ufw default deny' >> $path
            echo "ufw allow from ${DEPLOY_HOST} proto tcp to any port 5672 # DEPLOY_HOST" >> $path
            echo "ufw enable" >> $path
            ssh $ssh_string "mkdir -p ~/it490"
            scp "$path" "scp://$ssh_string/~/it490/"
        fi
    fi
    if [ "$target" == "all" ] || [ "$target" == "prod" ]; then
        if [ -z ${PROD_DB_USER} ] || [ -z ${PROD_DB_HOST} ]; then
            echo "PROD_DB_USER or PROD_DB_HOST not in cluster.ini"
        else
            ssh_string="$PROD_DB_USER@$PROD_DB_HOST"
            echo "Generating ufw scripts for PROD_DB_HOST ($ssh_string)"
            echo "#!/bin/bash" > $path
            echo 'ufw reset' >> $path
            echo 'ufw default deny' >> $path
            echo "ufw allow from ${DEPLOY_HOST} proto tcp to any port 5672 # DEPLOY_HOST" >> $path
            echo "ufw enable" >> $path
            ssh $ssh_string "mkdir -p ~/it490"
            scp "$path" "scp://$ssh_string/~/it490/"
        fi
    fi
fi
if [ "$type" == "all" ] || [ "$type" == "data" ]; then
    if [ "$target" == "all" ] || [ "$target" == "dev" ]; then
        if [ -z ${DEV_DATA_USER} ] || [ -z ${DEV_DATA_HOST} ]; then
            echo "DEV_DATA_USER or DEV_DATA_HOST not in cluster.ini"
        else
            ssh_string="$DEV_DATA_USER@$DEV_DATA_HOST"
            echo "Generating ufw scripts for DEV_DATA_HOST ($ssh_string)"
            echo "#!/bin/bash" > $path
            echo 'ufw reset' >> $path
            echo 'ufw default deny' >> $path
            echo "ufw allow from ${DEPLOY_HOST} proto tcp to any port 5672 # DEPLOY_HOST" >> $path
            if [ ! -z ${DEV_DB_HOST} ]; then
                echo "ufw allow from ${DEV_DB_HOST} proto tcp to any port 5672 # DEV_DB_HOST" >> $path
            fi
            echo "ufw enable" >> $path
            ssh $ssh_string "mkdir -p ~/it490"
            scp "$path" "scp://$ssh_string/~/it490/"
        fi
    fi
    if [ "$target" == "all" ] || [ "$target" == "qa" ]; then
        if [ -z ${QA_DATA_USER} ] || [ -z ${QA_DATA_HOST} ]; then
            echo "QA_DATA_USER or QA_DATA_HOST not in cluster.ini"
        else
            ssh_string="$QA_DATA_USER@$QA_DATA_HOST"
            echo "Generating ufw scripts for QA_DATA_HOST ($ssh_string)"
            echo "#!/bin/bash" > $path
            echo 'ufw reset' >> $path
            echo 'ufw default deny' >> $path
            echo "ufw allow from ${DEPLOY_HOST} proto tcp to any port 5672 # DEPLOY_HOST" >> $path
            if [ ! -z ${QA_DB_HOST} ]; then
                echo "ufw allow from ${QA_DB_HOST} proto tcp to any port 5672 # QA_DB_HOST" >> $path
            fi
            echo "ufw enable" >> $path
            ssh $ssh_string "mkdir -p ~/it490"
            scp "$path" "scp://$ssh_string/~/it490/"
        fi
    fi
    if [ "$target" == "all" ] || [ "$target" == "prod" ]; then
        if [ -z ${PROD_DATA_USER} ] || [ -z ${PROD_DATA_HOST} ]; then
            echo "PROD_DATA_USER or PROD_DATA_HOST not in cluster.ini"
        else
            ssh_string="$PROD_DATA_USER@$PROD_DATA_HOST"
            echo "Generating ufw scripts for PROD_DATA_HOST ($ssh_string)"
            echo "#!/bin/bash" > $path
            echo 'ufw reset' >> $path
            echo 'ufw default deny' >> $path
            echo "ufw allow from ${DEPLOY_HOST} proto tcp to any port 5672 # DEPLOY_HOST" >> $path
            if [ ! -z ${PROD_DB_HOST} ]; then
                echo "ufw allow from ${PROD_DB_HOST} proto tcp to any port 5672 # PROD_DB_HOST" >> $path
            fi
            echo "ufw enable" >> $path
            ssh $ssh_string "mkdir -p ~/it490"
            scp "$path" "scp://$ssh_string/~/it490/"
        fi
    fi
fi
