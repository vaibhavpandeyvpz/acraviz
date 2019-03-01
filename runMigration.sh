#!/usr/bin/env bash

envFile=.env

# check preconditions
if [ ! -f .env ]; then
    echo "Your environment is not set up correctly! Move the .env.example to .env, and edit your database (DB_*)" \
            "credentials."
fi


# set up environment variables from .env-file
while read line   # iterate over lines
do
    if [ ! -z ${line}  ]; then
        declare "${line}"
    fi
done <<< "$(cat ${envFile})" # this makes sure that the loop will not be executed in a subshell

export PHINX_DBHOST=${DB_HOST}
export PHINX_DBNAME=${DB_NAME}
export PHINX_DBUSER=${DB_USER}
export PHINX_DBPASS=${DB_PASSWORD}
export PHINX_DBPORT=${DB_PORT}
export PHINX_CHARSET=${DB_CHARSET}

vendor/bin/phinx migrate -e production