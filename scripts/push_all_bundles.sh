#!/bin/bash

if [ $# -ne 1 ]; then
    echo "pushing all bundles version 1"
    echo "scripts/push_all_bundles.sh [dev/qa/prod]"
fi
target=$1
if [ "$target" != "dev" ] && [ "$target" != "qa" ] && [ "$target" != "prod" ]; then
        echo "target is incorrect, should be [dev/qa/prod]"
        exit 1
fi

deploy/client.php push $target webDesignBun 1
deploy/client.php push $target loginBun 1
deploy/client.php push $target watchlistBun 1
deploy/client.php push $target webBun 1
deploy/client.php push $target registerBun 1
deploy/client.php push $target emailBun 1
deploy/client.php push $target extraBun 1
deploy/client.php push $target recBun 1
deploy/client.php push $target reviewBun 1
deploy/client.php push $target dbBun 1
deploy/client.php push $target dataBun 1
