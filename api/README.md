## Local setup

1. Clone the repo
 ```sh
   git clone https://github.com/oroalej/Aloware---EXAM.git
```

2. Set up environment variables

Copy `api/.env.example` to `api/.env`

3. config `.env`
```dotenv
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

5. Start the applications
```sh
php artisan key:generate
php artisan optimize
php artisan migrate
php artisan serve
```
