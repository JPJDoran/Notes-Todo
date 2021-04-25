## About

Started 25/04/21 using a clone of a base project that is nothing more than a fresh Laravel install.

## Installation

Laravel was used for the creation of this project and was hosted locally on a docker container using docker desktop. As such, if needed please configure docker:

- Mac: https://laravel.com/docs/8.x/installation#getting-started-on-macos
- Windows: https://laravel.com/docs/8.x/installation#getting-started-on-windows
- Linux: https://laravel.com/docs/8.x/installation#getting-started-on-linux

With docker installed we can now progress to installing the project.

1. Clone this repo to your local environment.
    1. If you're using windows you'll need to ensure you clone it to your WSL2 operating system to use docker desktop (see above).
2. `cd` into the project.
3. Open a new command line tab in this folder and run `./vendor/bin/sail up` to launch the project
4. Install composer dependencies with `composer install`
5. Install npm dependencies with `npm install`
6. Build js/css with `npm run dev`
7. Copy example env file `cp .env.example .env`
8. Generate an encryption key `php artisan key:generate`
9. Create a database on your local and update your .env file with the correct details
10. Migrate database tables `php artisan migrate`

## Limitations

Known limitations:

-

## Improvements

Potential improvements:

-
