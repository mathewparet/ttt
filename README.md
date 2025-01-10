# Tic-Tac-Toe

## Local Deployment

1. Git clone the application
2. Pull in the dependencies - `composer install`
3. Make config file:
```
cp .env.example .env
```
4. Configure DB env variables in the `.env` file. (Variables starting with `DB_`.)
5. Bring up the environment using sail `./vendor/bin/sail up -d` (This will run a bit long for the first time since it would be pulling docker images.) This will also setup the database defined in .env file.
6. Define database tables by running `./vendor/bin/sail artisan migrate`
7. Install NPM dependencies `./vendor/bin/sail npm install`
8. Build the UI `./vendor/bin/sail npm run build`
9. Visit http://localhost to access the application.