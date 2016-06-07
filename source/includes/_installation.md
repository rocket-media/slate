# Setup

## Installation

The `rmutil` utilities suite includes two commands for installing/updating Rocket forms. These should be run **from the root of the project**, e.g. from `~/Sites/cooltoday.com/`, and **on your local machine**. Their job is to get the Rocket Forms files into your project, very much like the `installAddon` script does with EE plugins.

<aside class="notice">
    Rocket Forms requires jQuery, so that will need to be included *before* <code>rocket-forms.prod.js</code>.
</aside>

## Craft/Booster Installation

1. From the **root of your project** run `$ rmutil forms install`.
2. If the install script detects a folder at `./craft` it will prompt you to install the Craft plugin.
3. Install the plugin inside of Craft (Admin->Plugins, then click the 'Install' button for Rocket Forms).
4. In the Craft plugin settings, provide the appropriate Wufoo API key and subdomain.
5. Include a link to the Rocket Forms CSS and JS in your project. If you're using booster, it will look something like this:

```html
<link rel="stylesheet" href="/rocket-forms/css/rocket-forms.css">
...
<!-- Make sure this is included _after_ jQuery -->
<script src="/rocket-forms/public/js/rocket-forms.prod.js"></script>
<!-- Or use the unminified, dev version  -->
<script src="/rocket-forms/public/js/rocket-forms.dev.js"></script>
```

The forms app will be installed into `./rocket-forms` and the plugin will be installed to `./craft/plugins/rmforms`.

## ExpressionEngine Installation

1. From the `httpdocs` folder run: `ln -s ../rocket-forms/public rocket-forms`.
<aside class="notice" style="">
    Remember, you will need to create this symlink on the server as well.
</aside>
1. From the **root of your project** run `$ rmutil forms install`.
2. If the install script detects a folder at `./ee` it will prompt you to install the ExpressionEngine plugin.
4. In the `./rocket-forms/.env` file, supply values for `WUFOO_API_KEY`, `WUFOO_SUBDOMAIN`, and `ROCKET_FORMS_API_KEY`.
5. Include a link to the Rocket Forms CSS and JS in your project:

```html
<link rel="stylesheet" href="/rocket-forms/css/rocket-forms.css">
...
<!-- Make sure this is included _after_ jQuery -->
<script src="/rocket-forms/public/js/rocket-forms.prod.js"></script>
<!-- Or use the unminified, dev version  -->
<script src="/rocket-forms/public/js/rocket-forms.dev.js"></script>
```

The forms app will be installed into `./rocket-forms` and the plugin will be installed to `./ee/third_party_addon/rm_forms`.
