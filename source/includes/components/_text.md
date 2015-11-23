# Components

Just about every Rocket Form field should be wrapped with a `<div class="rf-field">`. The affords the most control for the developer to be able to add classes to the label, or customize the markup that appears inside the label. However, we may remove this requirement and make the label part of the Vue component if it turns out that we're hardly customizing the label.

## Text Input

### Additional parameters

In addition to the [global parameters](#global-parameters), the text input accepts these as well:

Parameter    | Required | Default | Description
------------ | -------- | ------- | -----------
`type` | no | "text" | Specify the type of text input. Any valid HTML5 value is allowed.
`pattern` | no | null | Specify a validation regex pattern that the field must conform to.
`v-mask` | no | null | Specify a mask that the input will conform to. See the phone example below.

The standard text input can be used for generic text fields as well as phone and email fields.

### Standard

<form class="rf-form live" id="rfText1">
    <div class="rf-field">
        <label for="name" class="rf-label">Name:</label>
        <rf-text api-id="[FieldX]" name="name" required pattern="cats" :attrs="{'data-parsley-required-message': 'Please provide your name'}"></rf-text>
    </div>
</form>

```html
<!-- Standard -->
<div class="rf-field">
    <label for="name" class="rf-label">Name:</label>
    <rf-text api-id="[FieldX]" name="name" required :attrs="{'data-parsley-required-message': 'Please provide your name'}"></rf-text>
</div>
```

### Standard with `pattern`

<form class="rf-form live" id="rfText2">
    <div class="rf-field">
        <label for="name" class="rf-label">Eirlyse's favorite animal:</label>
        <rf-text api-id="[FieldX]" name="name" required pattern="cats" :attrs="{'data-parsley-required-message': 'Please provide your name', 'data-parsley-pattern-message': 'It does not say cats!!'}"></rf-text>
    </div>
</form>

```html
<!-- Standard -->
<div class="rf-field">
    <label for="name" class="rf-label">Name:</label>
    <rf-text api-id="[FieldX]" name="name" required pattern="cats" :attrs="{'data-parsley-required-message': 'Please provide your name', 'data-parsley-pattern-message': 'It does not say cats!!'}"></rf-text>
</div>
```

### Email

<form class="rf-form live" id="rfText3">
    <div class="rf-field">
        <label for="email" class="rf-label">Email:</label>
        <rf-text api-id="[FieldX]" type="email" name="email" required :attrs="{'data-parsley-error-message': 'Please provide a valid email'}"></rf-text>
    </div>
</form>

```html
<!-- Email -->
<div class="rf-field">
    <label for="email" class="rf-label">Email:</label>
    <rf-text api-id="[FieldX]" type="email" name="email" required :attrs="{'data-parsley-error-message': 'Please provide a valid email'}"></rf-text>
</div>
```

### Phone with `v-mask`

<form class="rf-form live" id="rfText4">
    <div class="rf-field">
        <label for="phone" class="rf-label">Phone:</label>
        <rf-text type="tel" api-id="[FieldX]" name="phone" v-mask="'(000) 000-0000'" :attrs="{'data-parsley-trigger': 'change', 'data-parsley-error-message': 'Please provide a valid phone number'}"></rf-text>
    </div>
</form>

```html
<!-- Phone -->
<div class="rf-field">
    <label for="phone" class="rf-label">Phone:</label>
    <rf-text type="tel" api-id="[FieldX]" name="phone" v-mask="'(000) 000-0000'" :attrs="{'data-parsley-trigger': 'change', 'data-parsley-error-message': 'Please provide a valid phone number'}"></rf-text>
</div>
```

Notice the additional `v-mask` [directive](http://vuejs.org/guide/custom-directive.html) used. This allows you to pass a mask pattern you wish to enforce. This utilizes the [jquery Mask Plugin](https://igorescobar.github.io/jQuery-Mask-Plugin/) so you may use any pattern it allows.

