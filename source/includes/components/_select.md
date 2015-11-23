## Select

### Additional parameters

In addition to the [global parameters](#global-parameters), the select input accepts these as well:

Parameter    | Required | Default | Description
------------ | -------- | ------- | -----------
`:options` | yes | - | Provide the options for the dropdown. This can be an array of objects or a variable on the Vue instance. See example below.
`default-option` | no | "-- Select --" | Specify the default option

### Passing options object

<form class="rf-form live" id="rfSelect1">
    <div class="rf-field">
        <label class="rf-label" for="animal">Favorite animal:</label>
        <rf-select api-id="FieldX" name="animal" required :options="[{label: 'Fish', value: 'fish'}, {label: 'Goat', value: 'goat'}]"></rf-select>
    </div>
</form>

```html
<div class="rf-field">
    <label class="rf-label" for="animal">Favorite animal:</label>
    <rf-select api-id="FieldX" name="animal" required :options="[{label: 'Fish', value: 'fish'}, {label: 'Goat', value: 'goat'}]"></rf-select>
</div>
```

### Binding options to a variable

If you have an extensive list of options, you will want to define them somewhere other than the form markup. This can be done like:

```html
<script>
    window.rfOptions.data.myOptions = [
        {label: 'Option 1', value: 'beans'},
        {label: 'Option 2', value: 'rice'},
        ...
    ];
</script>
```

This will allow you to bind the options like:

```html
<div class="rf-field">
    <label class="rf-label" for="date">Favorite animal:</label>
    <rf-select api-id="FieldX" name="date" required :options="myOptions"></rf-select>
</div>
```

