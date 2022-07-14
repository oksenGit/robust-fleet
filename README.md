# Robust Fleet

A Fleet Managment System

## Versions

- Laravel: 9.2.0
- PHP: 8.1.0
- MYSQL: 5.7.36
- MariaDB: 10.6.5
- php.ini and my.ini added to repo just in case

## Initialization

- clone the repo and run composer install
- copy .env.example into .env

### creating the database

#### easy way

- to create the database automatically import the robust-fleet.sql.zip SQL Dump

#### manually

- create database named `robust-fleet`
- run `php artisan migrate`
- import each table individually if you want the test data (disabling foreign key check will make your life easier)

### Running the server

run the server locally using `php artisan serve`

## Admin

### admin panel is create using Voyager

- To login into the Admin Panel go to <http://127.0.0.1:8000/admin> (change link and port if required)
- email: admin@admin.com
- password: 123456

## Endpoint

 the app only has two end points as the task requires but you can find authentication and user related endpoints in the authentication branch

## Available seats

- `GET: api/availableSeats?departure_city_id=1&destination_city_id=4`
get all available seats in all the trips that have station from  `departure_city_id` to `destination_city_id`
- response `[{"id":  62,"trip_id":  2}]`

## Book a Seat

- `POST: api/bookSeat` `body: {"seat_id":62, "departure_city_id":1, "destination_city_id":4}`
books a seat if the passed data is valid
response: `{"seat_id":  61,"departure_city_id":  1,"destination_city_id":  4,"updated_at":  "2022-07-08T09:19:49.000000Z","created_at":  "2022-07-08T09:19:49.000000Z","id":  1}`
