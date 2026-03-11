## Documenting stuff here

### Running locally

For the website to successfully run locally, the db, broker, and data processor must all be setup. Everything in the ini files should point to localhost for `MQ_HOST`. `db.php` and `data.php` should both be running.

### Run commands

Run commands are in the `rc` directory

`broker.sh`         -> run commands for the rabbitmq server \
`broker_clean.sh`   -> run commands for cleaning up all the stuff in rcbroker.sh \
`broker_purge.sh`   -> run commands for purging all of the queues of messages \
`db.sh`             -> run commands for the database server \
`db_clean.sh`       -> run commands for cleaning up all the stuff in rcdb.sh \
`db_purge.sh`       -> run commands for purging the cached api calls

execute these like `sudo rc/broker.sh`

### Ini files

`db_mysql.ini`        -> ini file for mysql in database, used in db.php \
`db_server.ini`       -> ini file for creating rabbitmq database server for communicating with database \
`db_client.ini`       -> ini file for creating rabbitmq database client for communicating with data \
`web_client.ini`      -> ini file for creating rabbitmq web client for communicating with broker \
`data_client.ini`     -> ini file for creating rabbitmq data server for communitcating with data \
`.api.ini`            -> ini file with our api keys

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
