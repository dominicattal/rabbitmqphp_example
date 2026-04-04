# Documentation

## Deploy

### Overview

Deploy system works by pushing a *bundle* with *type* either web, db, or data to a *target* either dev, qa, or prod. These bundles can be subsequently rolled back by supplying the *bundle_name* and *version*. The available bundles, the versions for each bundle, and the current versions on each target should all be queryable.

### Setup

Important but idk where to put this yet. Everything works in /tmp directories, so look there for the bundles and extractes stuff.

There are 10 vms, which are: 
```
deploy
dev_web
dev_db
dev_data
qa_web
qa_db
qa_data
prod_web
prod_db
prod_data
```
These are all defined in `deploy/clusters.ini`. Since we're all working on different machines, i made it an untracked file that you create by copying `deploy/clusters_sample.ini` as `deploy/clusters.ini` and filling the fields. You can update all of the cluster ini files (`dev_web_server.ini`, `dev_db_server.ini`, etc) by running `deploy/replace.sh`. You should set the user and host for each. These variables are used in scripts on this machine and also in the deploy vm to copy files.

Here are instructions on initial setup for each machine

1. First, you have to install ssh on each machine. Here's the commands for convenience
```
sudo apt update
sudo apt install ssh
sudo systemctl enable ssh
sudo systemctl restart ssh
```
2. Ensure ssh key exists on this machine.
3. Run `deploy/ssh_copy.sh`. This will automatically copy your ssh key to all of the machines. This is done so that deleting everything is easy.
4. Run `deploy/update.sh all all`. This will automatically create the it490 directory and copy necessary files to each machine defined in `clusters.ini`. If it is not defined yet, it will be skipped.
    - You can specify targets and type like `deploy/update.sh [deploy/dev/qa/prod/all] [web/db/data/all]`
    - You can specify which files get sent by specifying them after the target and type in the command line, so like
        `deploy/update.sh dev web deploy/dev_web_server.ini`
5. Setup deploy vm:
    1. `cd it490`
    2. On deploy vm, run `sudo apt.sh` to ensure necessary packages are installed
    3. On deploy vm, run `sudo broker.sh` to create rabbitmq stuff
        - if you want to delete the queues, you can do `sudo rabbitmqctl delete_vhost it490`
    4. Run `ssh_copy.sh all all` to create ssh key and copy them to other machines.
    5. Run `deploy.php` to listen for requests. This should be handled by systemd.
6. Setup {target} {type} vm
    1. `cd it490`
    2. Run `handler.php {target}_{type}_server.ini` to listen for requests.

### Push

Run `deploy/push.php [dev/qa/prod] [bundle]` to deploy a bundle to dev, qa, or prod. The structure of bundle should look like this:
```
bundle
|- info.ini
|- files
   |- installer.sh
   |- ...
   |- ...
   |- ...
```
`bundle` is a compressed directory that contains `info.ini` and `files` \
`info.ini` contains the bundle info that the deploy machine reads. It should have the following fields:
```
BUNDLE_NAME="test_bundle"
BUNDLE_HR_NAME="Test Bundle"
BUNDLE_DESC="This Bundle is a Test"
BUNDLE_TYPE="web"|"db"|"data"
```
`files` is a directory that contains `installer.sh` and all of the other files for the bundle \
`installer.sh` is called after the target vm unzips files. this should copy all of the files from this directory into their correct place in the project.

The deploy vm will make a copy of files and store it to be accessed by the database.
### Bundles
These files will be bundled together
```
dataBun
|- data.php
dbBun
|- db.php
|- schema.sql
|- db.sh
|- db_clean.sh
|- db_purge.sh
brokerBun
|- broker.sh
|- broker_clean.sh
|- broker_purge.sh
webBun
|- web.php
|- sample
   |-navbar.php
   |-search.php
   |-validation_handler.php
   |-header.php
   |.goutputstream-GY4MM3
loginBun
|- login.html
|- login_handler.php
registerBun
|- registration.html
|- registration_handler.php
extrasBun
|- home.php
|- higherlower.php
|- upcoming.php
emailBun
|- email.php
|- email_rec.php
webDesignBun
|- background.jpg
|- madd.css
reviewBun
|- details.php
|- get_reviews_handler.php
|- reviews.html
|- reviewsView.html
|- reviewsView_handler.php
|- reviews_handler.php
watchlistBun
|- watchlist.php
|- watchlist_add.php
|- watchlist_handler.php

```
### Running locally

For the website to successfully run locally, the db, broker, and data processor must all be setup. Everything in the ini files should point to localhost for `MQ_HOST`. `db.php` and `data.php` should both be running.

### Scripts

Scripts are in the `scripts` directory. Deployment scripts are in `deploy` directory

`scripts/broker.sh`         -> run commands for the rabbitmq server \
`scripts/broker_clean.sh`   -> run commands for cleaning up all the stuff in rcbroker.sh \
`scripts/broker_purge.sh`   -> run commands for purging all of the queues of messages \
`scripts/db.sh`             -> run commands for the database server \
`scripts/db_clean.sh`       -> run commands for cleaning up all the stuff in rcdb.sh \
`scripts/db_purge.sh`       -> run commands for purging the cached api calls
`deploy/ssh_copy.sh`        -> copy ssh keys to target machines specified in `deploy/clusters.ini`.
`deploy/update.sh`          -> directly update files on target machines. not to be confused with deploy system
`deploy/clear.sh`           -> remove the it490 directory that has all of the stuff.
`deploy/bundlify.sh`        -> turns a directory into a bundle. must look like the directory above
`deploy/replace.sh`         -> replaces all of the MQ_HOST fields in the server inis with the DEPLOY_HOST value in `deploy/clusters.ini`

execute these like `sudo scripts/broker.sh`

### Ini files

`db_mysql.ini`        -> ini file for mysql in database, used in db.php \
`db_server.ini`       -> ini file for creating rabbitmq database server for communicating with database \
`db_client.ini`       -> ini file for creating rabbitmq database client for communicating with data \
`web_client.ini`      -> ini file for creating rabbitmq web client for communicating with broker \
`data_client.ini`     -> ini file for creating rabbitmq data server for communitcating with data \
`.api.ini`            -> ini file with our api keys

`bundle_client.ini`   -> for deploying bundles \
`deploy_client.ini`   -> runs on deploy vm \
`deploy_server.ini`   -> runs on deploy vm \
`clusters.ini`        -> has all of the machine info. for use on main vm and deploy vm. \
`dev_web_server.ini`

The MQ_HOST field in the ini files should all point to the VM hosting the rabbitmq broker

The `.api.ini` must be created (since it is not tracked by git) and have the `TMDB_KEY`, `MADD_EMAIL`, and `MADD_PASS` fields.

### Webpage

For ease of development, I use the following command to start a php server instead of copying to the apache directory every time

    `php -S 127.0.0.1:8000 -t sample`

The script `copy_webpage.sh` will automatically move all the relevant files to the apache directory. \
This script hasn't been updated in a while so it probably wont work.

### Rabbitmq

We have 4 queues, `web_queue`, `db_web_queue`, `db_data_queue`, and `data_queue`. The routing keys to routing messages to each queue are `web`, `db_web`, `db_data`, and `data`.The routing keys are for the exchange to know which queue to publish to. Our exchange is direct, so the whole routing key will be matched to one whole binding. A binding is the relationship between a queue and its exchange. For example, the queue `web_queue` uses the binding `web` so that messages published to the exchanged with the routing key `web` are routed to that queue. `db_web_queue` and `db_data_queue` were previously just one queue, but it was necessary to split them into two queues because for some reason, having a server and client referencing the same queue at the same time breaks things. I believe the issue might have been fixed when I disconnected the connection in rabbitMQClient, so perhaps db_web_queue and db_data_queue can be consolidated.

### API

Add `TMDB_KEY` to `.api.ini`

Use tmdb for the data api (link https://developer.themoviedb.org/reference/getting-started)

To verify that the api keys are correct, run `./testtmdbapi.php`

### Email

We use `PHPMailer` for our email api (https://github.com/PHPMailer/PHPMailer) 

Make sure you run `composer install` and `composer update` 

Test that email works by running `./testemailapi.php`. It sends a simple email to ourselves.

### Database Endpoints

Documentation for types of queries you can make to DB VM

```
[type]
> [arg1] [type] description
> [arg2] [type] description
> [arg3] [type] description
< [ret1] [type] description
< [ret2] [type] description
< [ret3] [type] description
= description of query

[login]
> [username] [string] username of user trying to login
> [email] [string] email of user trying to login
> [password] [string] password of user trying to login
= attempts to login user. will genereate session key for user on success

[register]
> [username] [string] username of user trying to register
> [password] [string] password of user trying to register
= attempts to register a user. will generate session key for user on success.

[get_email]
> [username] [string]
< [email] [string]
= gets user's email

[movie]
> [id] [int] id of the movie to get
< [title] title of movie
< [overview] overview of movie
< [poster_img_url] full url path to the poster img
< [genre_id] the genre id of the movie
= gets the info about a movie

[watchlist]
> [username] [string] user whose watchlist should be fetched
= gets every movie in a user's watchlist

[add_watchlist]
> [username] [string] username to add review for
> [movie_id] [int] id of the movie to add to watchlist
> [movie_name] [int] name of the movie to add to watchlist
= adds a movie to a user's watchlist

[upcoming]
= gets upcoming movies

[recommend]
> [username] [string] user to recommend to
< [found] [bool] whether a movie was found to recommend based off of
< [movie_id] [string] if `found` is true, then it is the id of the movie used for recommendation
< [movie_title] [string] if `found` is true, then it is the title of the movie used for recommendation
< [results] [array] the recommended movies, formatted the same way as in movie
= gets some movies that the user might like. recommendations are based on reviews; if the user has one or more movies rated 7 or above, it will look for movies in the same genre. otherwise, it will just return popular movies
```

### Data Endpoints

Documentation for types of queries you can make to Data VM. \
If the frontend wants to call an endpoint, do it from the db.

```
[type]
> [arg1] [type] description
> [arg2] [type] description
> [arg3] [type] description
< [ret1] [type] description
< [ret2] [type] description
< [ret3] [type] description
= description of query

[movie]
> [id] [int] id of the movie to get
< [result] [array] all of the info of the movie
= gets all of the info relted to a movie

[popular]
= returns popular movies, basically just the basicaly tmdb api call

[genres]
< [result] [array] the genres and their ids
= returns all of the genres recognized by tmdb

[upcoming]
= gets upcoming movies

[popular_in_genre]
> [genre_id] [int] the id of the genre
< [result] [array] returned movies
= Like popular, except it gives the most popular by genre
    
```
