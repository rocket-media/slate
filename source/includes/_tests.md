# Tests

Tests exist for both the frontend and backend. The frontend uses a Javascript library called Nightwatch for end-to-end (e2e) tests, and the backend uses Codeception.

## Frontend

1. Install nighwatch.js globally
2. Configure the e2e host in MAMP (see `gulp/config.js`)
3. Run `gulp serve:e2e`
4. Run `nightwatch` (`nightwatch -a [tag]` to run only certain test tags)

## Backend

1. Make sure you have dev-dependencies installed locally by running (in rocket forms root) `composer install --dev`
2. Run the following to run all functional tests: `./src/vendor/bin/codecept run functional`
3. See [Codeception docs](http://codeception.com/docs/07-AdvancedUsage#Groups) or details on how to run individual tests.
