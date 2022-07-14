
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

- import each table individually if you want the test data (disabling forgein key check will make your life easier)

### Running the server

run the server locally using `php artisan serve`

## Admin

### admin panel is create using Voyager

- To login into the Admin Panel go to <http://127.0.0.1:8000/admin> (change link and port if required)

- email: admin@admin.com

- password: 123456

## Endpoint
  
The app includes the two required endpoints + some optional basic authentication endpoints
Note: Make sure to add `Accept: application/json` Request Header

## Authentication (optional)

### Register  

- `POST: api/register`
- `Body(JSON)`:`{"email":"", "password":"", "password_confirmation":""}`
- `Response`:`{"token":"","type":"Bearer"}`

### Login

- `POST: api/login`
- `Body(JSON)`:`{"email":"", "password":""}`
- `Response`:`{"token":"","type":"Bearer"}`

### Me

- `GET: api/me`
- `HEADERS`:`Authorization: Bearer $TOKEN`
- `Response`:`{"id":1,"email":"email@gmail.com","created_at":"2022-07-14T20:07:22.000000Z","updated_at":"2022-07-14T20:07:22.000000Z"}`

### My Reservations

- `GET: api/me/reservations`
- `HEADERS`:`Authorization: Bearer $TOKEN`
- `Response`:`[{"id":3,"user_id":1,"seat_id":63,"departure_city_id":1,"destination_city_id":4,"created_at":"2022-07-14T19:47:25.000000Z","updated_at":"2022-07-14T19:47:25.000000Z"}]`

## Available seats

- `GET: api/availableSeats?departure_city_id=1&destination_city_id=4`

get all available seats in all the trips that have station from `departure_city_id` to `destination_city_id`

- response `[{"id": 62,"trip_id": 2}]`

## Book a Seat

- `POST: api/bookSeat`  `body: {"seat_id":62, "departure_city_id":1, "destination_city_id":4}`
- `HEADERS`:`Authorization: Bearer $TOKEN` (optional - allows authed user to get his reservations)
books a seat if the passed data is valid

response: `{"seat_id": 61,"departure_city_id": 1,"destination_city_id": 4,"updated_at": "2022-07-08T09:19:49.000000Z","created_at": "2022-07-08T09:19:49.000000Z","id": 1}`
