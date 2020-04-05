## About

Simple Wallet is a simple backend apps that provide frontend with several useful API:
- Register : /api/register
- Login : /api/login
- Logout : /api/logout
- Deposit : /api/deposit
- Withdraw : /api/withdraw
- Transfer : /api/transfer

## Authentication
This apps use Json Web Token (JWT) as an authentication method for the API.

## API
The detail about API can be found in [Postman Collection](Indodax.postman_collection.json). You can also instantly try the API by importing that file to [Postman](https://www.postman.com/).

## How to run
This apps use docker compose to setup the environment. That is why you can run this apps by executing ```docker-compose up```.
It will automatically setup database, web server and another needed dependencies, so you can run the apps instantly on any other platform.

## License
The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
