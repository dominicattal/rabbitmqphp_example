#!/bin/bash
#Put in a file as the argument and that should grab all the related files to the bundle. it will then tell you which bundle to push to deploy
filename=$1;
if [[ $filename = "data.php" || $filename = "all" ]]; then
	cp data.php bundles/dataBun/files
	echo "dataBun";
fi
if [[ $filename = "db.php" || $filename = "schema.sql" || $filename = "db.sh" || $filename = "db_clean.sh" || $filename = "db_purge.sh" || $filename = "all" ]]; then
	cp db.php bundles/dbBun/files
	cp scripts/{schema.sql,db.sh,db_clean.sh,db_purge.sh} bundles/dbBun/files
	echo "dbBun";
fi
if [[ $filename = "broker_clean.sh" || $filename = "broker_purge.sh" || $filename = "all" ]]; then
	cp scripts/{broker_clean.sh,broker_purge.sh} bundles/brokerBun/files
	echo "brokerBun";
fi
if [[ $filename = "navbar.php" || $filename = "search.php" || $filename = "validation_handler.php" || $filename = "header.php" || $filename = "all" ]]; then
	cp sample/{navbar.php,search.php,validation_handler.php,header.php} bundles/webBun/files
	echo "webBun";
fi
if [[ $filename = "login.html" || $filename = "login_handler.php" || $filename = "all" ]]; then
	cp sample/{login.html,login_handler.php} bundles/loginBun/files
	echo "loginBun";
fi
if [[ $filename = "registration.html" || $filename = "registration_handler.php" || $filename = "all" ]]; then
	cp sample/{registration.html,registration_handler.php} bundles/registerBun/files
	echo "registerBun";
fi
if [[ $filename = "home.php" || $filename = "higherlower.php" || $filename = "upcoming.php" || $filename = "all" ]]; then
	cp sample/{home.php,higherlower.php,upcoming.php} bundles/extrasBun/files
	echo "extrasBun";
fi
if [[ $filename = "email.php" || $filename = "email_rec.php" || $filename = "all" ]]; then
	cp email.php bundles/emailBun/files
	echo "emailBun";
fi
if [[ $filename = "background.jpg" || $filename = "madd.css" || $filename = "all" ]]; then
	cp sample/{background.jpg,madd.css} bundles/webDesignBun/files
	echo "webDesignBun";
fi
if [[ $filename = "details.php" || $filename = "get_reviews_handler.php" || $filename = "reviews.html" || $filename = "reviewsView.html" || $filename = "reviewsView_handler.php" || $filename = "reviews_handler.php" || $filename = "all" ]]; then
	cp sample/{details.php,get_reviews_handler.php,reviews.html,reviewsView.html,reviewsView_handler.php,reviews_handler.php} bundles/reviewBun/files
	echo "reviewBun";
fi
if [[ $filename = "watchlist.php" || $filename = "watchlist_add.php" || $filename = "watchlist_handler.php" || $filename = "all" ]]; then
	cp sample/{watchlist.php,watchlist_add.php,watchlist_handler.php} bundles/watchlistBun/files
	echo "watchlistBun";
fi
if [[ $filename = "recommmend.php" || $filename = "recommend_hander.php" || $filename = "all" ]]; then
	cp sample/{recommend.php,recommend_handler.php} bundles/recBun/files
	echo "recBun";
fi

