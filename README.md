## Documenting stuff here

### Running locally

For the website to successfully run locally, the db, broker, and data processor must all be setup. Everything in the ini files should point to localhost for `MQ_HOST`. `db.php` and `data.php` should both be running when making requests from the web.

### Run commands

`rcbroker.sh`         -> run commands for the rabbitmq server \
`rcbroker_clean.sh`   -> run commands for cleaning up all the stuff in rcbroker.sh \
`rcbroker_purge.sh`   -> run commands for purging all of the queues of messages \
`rcdb.sh`             -> run commands for the database server \
`rcdb_clean.sh`       -> run commands for cleaning up all the stuff in rcdb.sh \
`rcdb_clear.sh`       -> run commands for clearing the cached api calls

execute these like `sudo ./rcbroker.sh`

### Ini files

`db_mysql.ini`        -> ini file for mysql in database, used in db.php \
`db_server.ini`       -> ini file for creating rabbitmq database server for communicating with database \
`db_client.ini`       -> ini file for creating rabbitmq database client for communicating with data \
`web_client.ini`      -> ini file for creating rabbitmq web client for communicating with broker \
`data_client.ini`     -> ini file for creating rabbitmq data server for communitcating with data

The MQ_HOST field in the ini files should all point to the VM hosting the rabbitmq broker

### Webpage

For ease of development, I use the following command to start a php server instead of copying to the apache directory every time

    `php -S 127.0.0.1:8000 -t sample`

The script `copy_webpage.sh` will automatically move all the relevant files to the apache directory. \
This script hasn't been updated in a while so it probably wont work.

### Rabbitmq

We have 4 queues, `web_queue`, `db_web_queue`, `db_data_queue`, and `data_queue`. The routing keys to routing messages to each queue are `web`, `db_web`, `db_data`, and `data`.The routing keys are for the exchange to know which queue to publish to. Our exchange is direct, so the whole routing key will be matched to one whole binding. A binding is the relationship between a queue and its exchange. For example, the queue `web_queue` uses the binding `web` so that messages published to the exchanged with the routing key `web` are routed to that queue. `db_web_queue` and `db_data_queue` were previously just one queue, but it was necessary to split them into two queues because for some reason, having a server and client referencing the same queue at the same time breaks things. I believe the issue might have been fixed when I disconnected the connection in rabbitMQClient, so perhaps db_web_queue and db_data_queue can be consolidated.

### API

Use tmdb for the api (link https://developer.themoviedb.org/reference/getting-started) \
Create file called `.api.ini` in this directory and run `./testapi.php` to test connectivity

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
> [password] [string] password of user trying to login
= attempts to login user. will genereate session key for user on success

[register]
> [username] [string] username of user trying to register
> [password] [string] password of user trying to register
= attempts to register a user. will generate session key for user on success.

[movie]
> [id] [int] id of the movie to get
< [title]               title of movie
< [overview]            ovewview of movie
< [poster_img_url]      full url path to the poster img
= gets the info about a movie

[watchlist]
> [username] [string] user whose watchlist should be fetched
= gets every movie in a user's watchlist

[add_watchlist]
> [username] [string] username to add review for
> [movie_id] [int] id of the movie to add to watchlist
> [movie_name] [int] name of the movie to add to watchlist
= adds a movie to a user's watchlist

[recommend]
> [username] [string] user to recommend to
< [result] [array] array of movies
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
> [count] [int] the number of movies to return
= returns popular movies, basically just the basicaly tmdb api call

[genres]
< [result] [array] the genres and their ids
= returns all of the genres recognized by tmdb

[popular_in_genre]
> [genre_id] [int] the id of the genre
< [result] [array] returned movies
= Like popular, except it gives the most popular by genre
    
```
