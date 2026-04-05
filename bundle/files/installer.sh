#!/bin/bash
BUNDLE_NAME=$(awk -F "=" '/BUNDLE_NAME/ {print $2}' ../info.ini);
echo "Hello World"          # <--- this gets sent to $output array
`echo "Goodbye World" >&2`  # <--- this prints to stderr
if [[ $BUNDLE_NAME =~ "dataBun" ]]; then
	./data.php
fi
if [[ $BUNDLE_NAME =~ "dbBun" ]]; then
	./db_purge.sh
	./db_clean.sh
	./db.sh
fi
if [[ $BUNDLE_NAME =~ "brokerBun" ]]; then 
	./broker_purge.sh
	./broker_clean.sh
	./broker.sh
fi
if [[ $BUNDLE_NAME =~ "webBundle" ]]; then
	php -S 127.0.0.1:8000 -t sample
fi
