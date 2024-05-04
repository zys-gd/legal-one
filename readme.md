# Test work

## Local installation

Requirements:

* docker >= 24
* docker compose >= 2.26

Install application with `make install`

## Run application

Start application with `make run`

### Run sync command

Execute sync command with `docker compose run --rm php bin/console app:sync:logs %absolute_path_to_log_file%`

It is batch operation, you can adjust size of the batch editing `app/.env#BATCH_LINES_COUNT`

The step is cached, each command's run starts from previous line of the log file

# Disclaimer

I kept simple default symfony structure because it satisfies task requirements.

I did not write Unit tests because nothing to cover with them, thats why only Functional exists.

I did not put any indexes to the Log table, because it is not necessary for this task (not because I did not think about this). 
