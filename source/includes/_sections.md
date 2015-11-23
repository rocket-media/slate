# Sections

Forms can be split into sections. Only one section is expanded at a time and each section must validate before the user can continue.

## Example

<form class="rf-form live" id="rfSections1">
    <rf-section max-height="27em">
        <h3 slot="title">Section 1</h3>
        <div class="rf-field">
            <label for="name" class="rf-label">Name:</label>
            <rf-text api-id="Field1" name="name" required :attrs.once="{'data-parsley-required-message': 'Please provide your name'}"></rf-text>
        </div>
        <rf-section-next class="button" v-if="!complete">Continue</rf-section-next>
    </rf-section>

    <rf-section>
        <h3 slot="title">Section 2</h3>
        <div class="rf-field">
            <label class="rf-label" for="date">Favorite animal:</label>
            <rf-select api-id="FieldX" name="date" required :options.once="[{label: 'Fish', value: 'fish'}, {label: 'Goat', value: 'goat'}]"></rf-select>
        </div>
        <p>
            <a href="#" class="button" v-on:click="submit">Send message</a>
            <input class="is-hidden" type="submit">
        </p>
    </rf-section>
</form>

<br><br>

```html
<rf-section max-height="27em">
    <h2 slot="title">Section 1</h2>
    <div class="rf-field">
        <label for="name" class="rf-label">Name:</label>
        <rf-text api-id="Field1" name="name" required :attrs="{'data-parsley-required-message': 'Please provide your name'}"></rf-text>
    </div>
    <rf-section-next class="button" v-if="!complete">Continue</rf-section-next>
</rf-section>

<rf-section>
    <h2 slot="title">Section 2</h2>
    <div class="rf-field">
        <label class="rf-label" for="date">Favorite animal:</label>
        <rf-select api-id="FieldX" name="date" required :options="[{label: 'Fish', value: 'fish'}, {label: 'Goat', value: 'goat'}]"></rf-select>
    </div>
    <p>
        <a href="#" class="button" v-on:click="submit">Send message</a>
        <input class="is-hidden" type="submit">
    </p>
</rf-section>
```

## Creating Sections

This tag wraps each section:

```html
<rf-section>
    ...
</rf-section>
```

Parameter    | Required | Default | Description
------------ | -------- | ------- | -----------
`title`     | no | - | If provided, will populate an `<h2>` as the section title.
`max-height` | no | "30em" | Provide a max-height large enough to accommodate all the content in the section

### Specifying the Section Title

```html
<rf-section title="My Section">...</rf-section>
```
will be rendered as:

```html
<div class="rf-section" id="section1">
    <h2 slot="title">Section 2</h2>
    ...
</div>
```

If you'd rather control the markup for the section title, you may do so by adding the `slot` attribute to your title element:

```html
<rf-section>
    <h4 slot="title">My Custom Title</h4>
    ...
</rf-section>
```

### Specifying `max-height`

When moving from one section to the next, there is a nice smooth animation, which is acheived by transitioning the `max-height` property of the section. Because section content will be variable, you must specify one to accommodate yours. An ideal max-height is just large enough to include all the content of your field, though it can, of course, be larger. It's just can't be smaller.

```html
<rf-section max-height="50em">
    ...
</rf-section>
```

<aside class="notice">
    Don't forget to consider the additional height required when field validation is present!
</aside>

## Moving between sections

To allow your dear user to continue to the next section, use the `<rf-section-next>` component:

```html
<rf-section-next>Continue</rf-section-next>
```

Parameter    | Required | Default | Description
------------ | -------- | ------- | -----------
`class`     | no | - | To class to apply to the rendered `<a>` tag.

```html
<rf-section-next class="button">Continue</rf-section-next>
```

will render:

```html
<a href="#" class="button">Continue</a>
```

## Submitting the form

You may use whatever element you want to submit the form, but you must bind the `submit` method to it. I also recommend you include the standard `<input type="submit">` element as well so that pressing "return" submits the form as usual. You'll want to hide this element using whatever invisibility class you have set up.

```html
<a href="#" v-on:click="submit">Send message</a>
<input class="is-hidden" type="submit">
```




