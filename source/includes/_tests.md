# Tests

Tests exist for both the frontend and backend. The frontend uses a Javascript library called Nightwatch for end-to-end (e2e) tests, and the backend uses Codeception.

## Frontend

1. Install nighwatch.js globally
2. Configure the e2e host in MAMP (see `gulp/config.js`)
3. Run `gulp serve:e2e`
4. Run `nightwatch` (`nightwatch -a [tag]` to run only certain tags)

## Backend

Make sure you have dev-dependencies installed locally then run

`./src/vendor/bin/codecept run functional`

Note: Toggle `APP_ENV` in `.env` from `local` to `production` because some tests require either/or.
