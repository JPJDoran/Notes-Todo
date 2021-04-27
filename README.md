## About

Started 25/04/21 using a clone of a base project that is nothing more than a fresh Laravel install.

On registration of a new user, a default category, list and item is added to their account to provide an example for the user to build upon. Categories can be added to group lists by a common theme for example "work", lists can be added to a category for example "presentation prep" and each list can have any number of items for example "print agenda". Users can edit categories, lists and items as they wish and can mark list items as done once complete.

The system fufills the basic requirements of an MVP with some essence of flair, for example; the inclusion of user profile icons from https://eu.ui-avatars.com/.

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

- The UI is a bit garish.
- There wasn't sufficient time to refactor code, so currently everything sites in one controller. Ideally this controller could be split into a controller for 'categories', 'lists' and 'items'.

## Improvements

Potential improvements:

- Improve the UI and remove the overuse of edit icons.
- Go back through and refactor code. As well as the controller splitting above, the js could also be improved.
- Implement draggable or similar to provide item ordering.
- Provide options to show/hide completed items.
- Provide functionality to archive categories and/or lists.
