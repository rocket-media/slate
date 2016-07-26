## Datepicker

### Additional parameters

In addition to the [global parameters](#global-parameters), the datepicker components accepts these as well:

Parameter    | Required | Default | Description
------------ | -------- | ------- | -----------
`datepicker-options` | no | - | Provide an options object for [jQuery UI datepicker](http://api.jqueryui.com/datepicker/).
`on-select` | no | - | Provide a callback that will be executed each time the user selects a date. E.g. `on-select="function(date, instance) { ... }"`

<form class="rf-form live" id="rfDatepicker1">
    <div class="rf-field rf-datepicker">
        <label for="date" class="rf-label">Select a date:</label>
        <rf-datepicker api-id="FieldX" required name="date" :attrs="{'data-parsley-error-message': 'Please provide a date'}"></rf-datepicker>
    </div>
</form>

```html
<div class="rf-field rf-datepicker">
    <label for="date" class="rf-label">Select a date:</label>
    <rf-datepicker api-id="FieldX" required name="date" :attrs="{'data-parsley-error-message': 'Please provide a date'}"></rf-datepicker>
</div>
```
