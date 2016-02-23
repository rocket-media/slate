# General Usage

Here's the markup for a sample form:

```html
<form class="rf-form --loading" id="rocketForm" form-id="[WufooFormId]">
    <div class="rf-form__body">
        <h3 class="error" v-if="status.error">Error: {{status.error}}</h3>
        <div class="rf-field">
            <label for="name" class="rf-label">Name:</label>
            <rf-text api-id="Field1" name="name" required :attrs.once="{'data-parsley-required-message': 'Please provide your name'}"></rf-text>
        </div>
        ...
    </div>
</form>
<!-- Fallback for users w/o JS -->
<noscript>
    <iframe height="577" allowTransparency="true" frameborder="0" scrolling="no" style="width:100%;border:none" src="https://example.wufoo.com/embed/xx3234fsdwrfg/">
        <a href="https://example.wufoo.com/embed/xx3234fsdwrfg/">Fill out this form.</a>
    </iframe>
</noscript>
```

And to initialize the form:

```html
<script>
    var rocketForm = new Vue(window.rfOptions);
</script>
```

The form is not initialized by default . If you have multiple forms used throughout the site, you may want to initialize all of them like:

```html
<script>
    if ($('#rocketForm').length) {
        var rocketForm = new Vue(window.rfOptions);
    }  
</script>
```

<aside class="notice">
    By default, Vue will look for an element with <code>id="rocketForm"</code> to intialize.
</aside>

## `window.rfOptions`

As you've noticed, forms are not initialized automatically. This is because sometimes we'll want to customize certain aspects or add additional data (like [select input options](#select)) and functionality to forms before they're compiled. That's where `window.rfOptions` comes in. Rocket Forms makes the default Vue configuration available on the global window object as `window.rfOptions`. This allows you to customize or extend functionality before compiling your form.

## Global Parameters

These are global parameters that apply to (almost?) every Rocket Forms component. Field-specific parameters can be found in the [components](#components) section.

Parameter    | Required | Description
------------ | ------- | -----------
`api-id` | yes | This is the field name expected by the Wufoo API. See the [Finding Your Key](http://help.wufoo.com/articles/en_US/SurveyMonkeyArticleType/Wufoo-REST-API-V3) section on the Wufoo docs.
`name` | yes | The name of the field. Should be unique. Will become the `name` attribute on the `<input>` element.
`class` | no | Additional classes to be appended to the component.
`required` | no | Whether the field is required. By default, fields are not requried.
`:attrs` | no | An object of addtional attributes to be applied to the compiled `<input>` element. This will mostly be used to supply custom Parsley.js error messages.

```html
<rf-text
    api-id="[FieldX]"
    name="first_name"
    class="blue rounded"
    id="first_name"
    required
    :attrs="{'attribute': 'value'}"
></rf-text>
```

## Validation

Since we're using [Parsley.js](http://parsleyjs.org/) for the frontend validation, any valid Parsley data attribute can be passed to the field. See the [Parsley form options](http://parsleyjs.org/doc/index.html#psly-usage-form) for details.

