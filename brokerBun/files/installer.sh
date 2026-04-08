#!/bin/bash
BUNDLE_NAME=$(awk -F "=" '/BUNDLE_NAME/ {print $2}' /tmp/info.ini);
echo "Hello World"          # <--- this gets sent to $output array
`echo "Goodbye World" >&2`  # <--- this prints to stderr
echo $BUNDLE_NAME
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
if [[ $BUNDLE_NAME =~ "emailBun" ]]; then
	echo "email";
fi
if [[ $BUNDLE_NAME =~ "webBundle" ]]; then
	if [ ! -d sample ]; then
		mkdir sample
	fi
	mv navbar.php search.php validation_handler.php header.php sample/
	php -S 127.0.0.1:8000 -t sample
fi
if [[ $BUNDLE_NAME =~ "loginBun" ]]; then
	if [ ! -d sample ]; then
		mkdir sample
	fi
	mv login.html login_handler.php sample/
fi
if [[ $BUNDLE_NAME =~ "registerBun" ]]; then
	if [ ! -d sample ]; then
		mkdir sample
	fi
	mv registration.html registration_handler.php sample/
fi
if [[ $BUNDLE_NAME =~ "extrasBun" ]]; then 
	if [ ! -d sample ]; then
		mkdir sample
	fi
	mv home.php higherlower.php upcoming.php sample/
fi
if [[ $BUNDLE_NAME =~ "webDesignBun" ]]; then
	if [ ! -d sample ]; then
		mkdir sample
	fi
	mv background.jpg madd.css sample/
fi
if [[ $BUNDLE_NAME =~ "reviewBun" ]]; then
	if [ ! -d sample ]; then
		mkdir sample
	fi
	mv /tmp/files/{details.php,get_reviews_handler.php,reviews.html,reviewsView.html,reviewsView_handler.php,reviews_handler.php} sample/
fi
if [[ $BUNDLE_NAME =~ "watchlistBun" ]]; then
	if [ ! -d sample ]; then 
		mkdir sample
	fi
	mv watchlist.php watchlist_add.php watchlist_handler.php sample/
fi

