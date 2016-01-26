## Configuration

Environment-specific config settings can be added to the `./rocket-forms/.env` file. Allowable options are:

* `APP_ENV=local` By default, your local install will set the app environment to `local`. As such, forms will not be submitted to Wufoo; rather you'll see some debug info dumped to the console. If you want to actually test submission to Wufoo, change this value to `production`.
* `REQUEST_KEY_ERROR_USES` By default, a request key is valid for `5` erroneous requests. If you wish to adjust this value, you can set it like `REQUEST_KEY_ERROR_USES=10`.

The other values in this file are used by the app, but can, for the most part, be left alone and don't need to be changed:

* `ROCKET_FORMS_API_KEY` This key is used when Craft asks for a request key from the backend. By default, the Craft plugin install process sets this value and can be changed from the plugin settings screen in Craft.
* `WUFOO_API_KEY` The Wufoo API key. This should be set from the plugin settings screen in Craft.
* `WUFOO_SUBDOMAIN` The subdomain associated with the Wufoo account. This should be set from the plugin settings screen as well.
* `ROCKET_FORMS_INSTALL_PATH` When doing local development, you'll need to supply this path.

You will see some other options in the `.env` file; these are defaults used by the Lumen framework. See 'Major Components' section below.