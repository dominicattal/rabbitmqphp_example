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

### Rabbitmq Diagram
![rabbitmq_diagram](https://github.com/dominicattal/rabbitmqphp_example/blob/webpage/it490.png?raw=true)

Out of date, broker_queue no longer exists since it isn't necessary for the project. Also, exchange is now direct instead of fanout, so '#' routing key doesn't work.
