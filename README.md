## Documenting stuff here

### Run commands

`rcbroker.sh`         -> run commands for the rabbitmq server \
`rcbroker_clean.sh`   -> run commands for cleaning up all the stuff in rcbroker.sh \
`rcdb.sh`             -> run commands for the database server \
`rcdb_clean.sh`       -> run commands for cleaning up all the stuff in rcdb.sh

execute these like `sudo ./rcbroker.sh`

### Ini files

`db_mysql.ini`        -> ini file for mysql in database, used in db.php \
`db_server.ini`       -> ini file for creating rabbitmq database server for communicating with database \
`broker_server.ini`   -> ini file for creating rabbitmq broker server for communicating with web client \
`web_client.ini`      -> ini file for creating rabbitmq web client for communicating with broker

The MQ_HOST field in the ini files should all point to the VM hosting the rabbitmq broker

### Webpage

For ease of development, I use the following command to start a php server instead of copying to the apache directory every time \
    `php -S 127.0.0.1:8000 -t sample`

The script `copy_webpage.sh` will automatically move all the relevant files to the apache directory

### Rabbitmq

We have 3 queues, `web_queue`, `db_queue`, and `data_queue`. The routing keys to routing messages to each queue are `web`, `db`, and `data`.

### API

Use tmdb for the api (link https://developer.themoviedb.org/reference/getting-started) \
Create file called `.api.ini` in this directory and run `./testapi.php` to test connectivity

### Database Endpoints

Documentation for types of queries you can make to DB VM

```
[type]
- [field1] [type] description
- [field2] [type] description
- [field3] [type] description
= description of query

[login]
- [username] [string] username of user trying to login
- [password] [string] password of user trying to login
= attempts to login user. will genereate session key for user on success

[register]
- [username] [string] username of user trying to register
- [password] [string] password of user trying to register
= attempts to register a user. will generate session key for user on success.
```

### Data Endpoints

Documentation for types of queries you can make to Data VM

```
[type]
- [field1] [type] description
- [field2] [type] description
- [field3] [type] description
= description of query

[popular]
- [count] [int] the number of movies to return
= returns popular movies, basically just the basicaly tmdb api call
    
```
