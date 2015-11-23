## Radio

### Additional parameters

In addition to the [global parameters](#global-parameters), the radio input accepts these as well:

Parameter    | Required | Description
------------ | -------- | -----------
`label`     | no | Provide the label for the field. This can be a string or a Vue method/data.
`:options` | yes | Provide the options for the radios. This can be an array or a variable on the Vue instance. See example below.

<form class="rf-form live" id="rfRadio1">
    <rf-radios api-id="FieldX" name="serviceType" label="What kind of service do you need?" :options="['Repair', 'New replacement or install quote', 'Maintenance visit']" required></rf-radios>
</form>

```html
<rf-radios api-id="FieldX" name="serviceType" label="What kind of service do you need?" :options="['Repair', 'New replacement or install quote', 'Maintenance visit']" required></rf-radios>
```

<aside class="notice">
    Note: This is (currently) the only field that doesn't need to be wrapped with <code>&lt;div class="rf-field"&gt;</code>
</aside>