#! /usr/bin/env bash
set -e

echo "API USER NAME : $API_USER"

# Set up the basic DB and its associated user on container creation
psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "$POSTGRES_DB" <<- EOSQL
        CREATE USER $API_USER WITH ENCRYPTED PASSWORD '$API_PASSWORD';
        CREATE DATABASE $API_DB;
        GRANT ALL PRIVILEGES ON DATABASE $API_DB TO $API_USER;
EOSQL

# Needed to allow DB operations for the new user
psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "$API_DB" <<-EOSQL
    CREATE EXTENSION IF NOT EXISTS postgis;
    GRANT ALL ON SCHEMA public TO $API_USER;
EOSQL