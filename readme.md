# Test work

## Local installation

Requirements:

* docker >= 24
* docker compose >= 2.26

Install application with `make install`

For phpstorm users: Docker image contains xdebug. To use it you need only to add a server to the 'server' section with the name 'legal-one' and enable and configure path mapping. 

## Run application

Start application with `make run`

### Run sync command

Execute sync command with `docker compose run --rm php bin/console app:sync:logs %absolute_path_to_log_file%`

It is batch operation, you can adjust size of the batch editing `app/.env#BATCH_LINES_COUNT`

The step is cached, each command's run starts from previous line of the log file

# Disclaimer

I kept simple default symfony structure because it satisfies task requirements (KISS).

I did not write Unit tests because nothing to cover with them, that's why only Functional exist.

I did not put any indexes to the Log table, because it is not necessary for this task (not because I did not think about it). 
