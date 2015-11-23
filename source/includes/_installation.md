# Setup

## Installation

The `rmutil` utilities suite includes two commands for installing/updating Rocket forms. These should be run **from the root of the project**, e.g. from `~/Sites/cooltoday.com/`, and **on your local machine**. Their job is to get the Rocket Forms files into your project, very much like the `installAddon` script does with EE plugins.

<aside class="notice">
    Currently, Rocket Forms only works with Booster projects using Craft.
</aside>

1. From the **root of your project** run `$ rmutil forms install`.
2. If the install script detects a folder at `./craft` it will prompt you to install the Craft plugin.
3. Install the plugin inside of Craft (Admin->Plugins, then click the 'Install' button for Rocket Forms).
4. In the Craft plugin settings, provide the appropriate Wufoo API key and subdomain. 
5. Include a link to the Rocket Forms CSS and JS in your project. If you're using booster, it will look something like this:

```html
<link rel="stylesheet" href="/rocket-forms/public/css/rocket-forms.css">
...
<!-- jQuery is required -->
<script src="/src/bower/jquery/dist/jquery.js"></script>
<script src="/rocket-forms/public/js/rocket-forms.min.js"></script>
```

The forms app will be installed into `./rocket-forms` and (if you're using Craft), the plugin will be installed to `./craft/plugins/rmforms`. These paths cannot be customized at this time.

<aside class="notice">
    Rocket Forms requires jQuery, so that will need to be included *before* <code>rocket-forms.min.js</code>.
</aside>