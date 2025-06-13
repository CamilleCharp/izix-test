# 🔌 EV Charge Monitor

This project is a simple application to **monitor electric vehicle charging state**. It includes a **Vue.js frontend**, a **Laravel API backend**, and a **simulator** that generates synthetic charging data. The application uses **PostgreSQL** as its database.

# IMPORTANT NOTE

This project is not what I wanted to provide initially, you'll find multiple strange things here, to name a few :

- A full API tested but mostly unused.
- An inertia frond-end that contradict the V1 of this README, and is not tested.
- The project have to be tested locally even though it has everything to be ran into docker containers

The main reason behind that is problems I ran into when developping the front-end at the end of the project, My front, which was initially a completely separate SPA application couldn't handle authentication with the API. I think part of the problem is due to my browser but I didn't have time to test everything. In this situation I had to make a quick choice on how to handle this. 

In the end, the day before the deadline, I decided to rework the whole laravel application from an API to a full-stack app with Inertia. I was mostly successfull at converting everything, reusing my logic etc... But this led to a whole new bunch of problems with Docker, which I think I could fix if I had another day (I'll try, If you don't see this part anymore I succeeded), basically refusing to serve my pages even with a complete build.

So, in order to respect the week I was given, I give you this version, everything work, just not in the way I planned to.

TL;DR: Front-end SPA bugged, had to remake the whole app but couldn't get docker to work with it in time.

## Usage

### Postgres

This project expect a PostgreSQL database with PostGIS. Once you have that here are the operations I did to create the database : 

```
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
```

- `$POSTGRES_USER`: The postgres root user
- `$POSTGRES_DB`: The associated postgres database
- `$API_USER`: The app user name to postgres
- `$API_PASSWORD`: The app user password to postgres

### Application

Once you have the database ready, don't forget to add it to your .env or in a system variable (`export YOUR_VARIABLE=value`).

Now to get the dependencies run `composer install` and `npm install`

After that you can read the migrations and seeding using `php artisan migrate --seed` in the app directory. The seeds include a default admin user with `admin@admin.ad` email and `admin` password.

Finally, to run the app you need to launch the dev server using `composer run dev`.

After that the app is accessible from http://localhost:8000, if it doesn't work check if another port has not been assigned.

### Simulator

To simulate the charge of a vehicle, with informations that would normally be sent from a charging station, a simulator is available. To run it simply install the dependencies in the simulator folder with `composer install`, then you can run the server with `php artisan serve`. After that the simulator will be available from 127.0.0.1:8001 (or possibly another port), if it isn't on 8001 take care to change the port in the .env file

After that the simulator can be run with a single POST request at /charge/start, this request accepts differents parameter :

- `vehicle_uuid` : (required) The uuid of the vehicle to charge
- `connector_uuid` : (required) The uuid of the connector to use
- `speed` : The speed multiplicator to apply to the simulation, by default 200 times
- `starting_charge`: How much battery does the vehicle start with, in %.

## End notes

I'd like to thank Izix for this opportunity, I realize my project may not show the better side of my knowledge and I hope I can explain my thinking process and strength in more details soon, enjoy the app !

