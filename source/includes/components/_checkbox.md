## Checkbox

### Additional parameters

In addition to the [global parameters](#global-parameters), the checkbox input accepts these as well:

Parameter    | Required | Description
------------ | -------- | -----------
`label`     | yes | Provide the label for the checkbox

<form class="rf-form live" id="rfCheckbox1">
    <div class="rf-field">
        <p class="rf-label --strong">Select all the good things:</p>
        <div class="rf-checkboxes">
            <rf-checkbox name="show" api-id="FieldX" label="Star Trek"></rf-checkbox>
            <rf-checkbox name="show" api-id="FieldX" label="Lord of the Rings"></rf-checkbox>
            <rf-checkbox name="show" api-id="FieldX" label="Star Wars"></rf-checkbox>
            <rf-checkbox name="show" api-id="FieldX" label="Machform"></rf-checkbox>
            <div class="rf-errors"></div>
        </div>
    </div>
</form>

```html
<div class="rf-field">
    <p class="rf-label --strong">Select all the good things:</p>
    <div class="rf-checkboxes">
        <rf-checkbox name="show" api-id="FieldX" label="Star Trek"></rf-checkbox>
        <rf-checkbox name="show" api-id="FieldX" label="Lord of the Rings"></rf-checkbox>
        <rf-checkbox name="show" api-id="FieldX" label="Star Wars"></rf-checkbox>
        <rf-checkbox name="show" api-id="FieldX" label="Machform"></rf-checkbox>
        <div class="rf-errors"></div>
    </div>
</div>
```