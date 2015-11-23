# Local Development

There are two ways to use Rocket Forms locally:

## Production Version

This will use a locally installed production version of the project and is the preferred method when you don't need to do any new development on RF itself.

1. Follow the install and/or update instructions above to install RF into the project.
2. In your `.tmp` folder (or whatever folder BrowserSync is serving from) create a symlink to the RF install like `ln -sfn ../rocket-forms rocket-forms` (The exact path may need to be adjusted depending upon where your `.tmp` folder is in relation to the `rocket-forms` install dir). This symlink makes it so your references to the RF CSS/JS files will work when serving locally.
3. In your `.tmp` folder, add another symlink like `ln -sfn ../rocket-forms/public rocket-forms-dev`. This symlink is used by the RF Craft plugin when getting request keys, etc. This needs to be different than the symlink we created in step 2 because it needs to point to the `pubic` folder within `rocket-forms`.
3. In `rocket-forms/.env` add `ROCKET_FORMS_INSTALL_PATH=rocket-forms-dev`. This tells the RF Craft plugin and local forms where to get request keys and where to submit entries to.

## Development Version

This will use a locally installed development (unbuilt, unminified, etc.) version of the RF, and is the method you should use when you need to actually make changes to the RF codebase.

1. In the root of your project, create a symlink that points to the development version of the RF repo, e.g. `ln -sfn /Users/ghenderson/code/rocket-forms rocket-forms.dev` (or whever you've cloned the RF repo).
2. In your `.tmp` folder create both symlinks as described above, only instead of targeting the `rocket-forms` folder, target the symlink you created in step 1. So you should have something like:
    `.tmp/rocket-forms` which is a symlink to `../rocket-forms.dev/src` and  
    `.tmp/rocket-forms-dev` which is a symlink to `../rocket-forms.dev/src/public`
3. In your `_layout.twig` or whatever you're including the CSS/JS for RF, update the JS links to reference the individual, unbuilt files (the CSS file path will be the same):

```html
<script src="/rocket-forms/public/bower/parsleyjs/dist/parsley.js"></script>
<script src="/rocket-forms/public/bower/jQuery-Mask-Plugin/dist/jquery.mask.js"></script>
<script src="/rocket-forms/public/bower/jquery-ui/ui/datepicker.js"></script>
<script src="/rocket-forms/public/bower/spin.js/spin.js"></script>
<script src="/rocket-forms/public/bower/spin.js/jquery.spin.js"></script>
<script src="/rocket-forms/public/bower/rangeslider.js/dist/rangeslider.js"></script>
<script src="/rocket-forms/public/js/rocket-forms.tmp.js"></script>
```