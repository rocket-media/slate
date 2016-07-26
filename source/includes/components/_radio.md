## Radio

### Additional parameters

In addition to the [global parameters](#global-parameters), the radio input accepts these as well:

Parameter    | Required | Description
------------ | -------- | -----------
`label`     | no | Provide the label for the field. This can be a string or a Vue method/data.
`sub-label`     | no | Provide a sub-label for the field. This can be a string or a Vue method/data.
`:options` | yes | Provide the options for the radios. This can be an array or a variable on the Vue instance. See example below.
`fallback` (deprecated) | no | If there are no available options, display a fallback message. This is deprecated. Instead you should simply include any fallback markup directly between the opening and closing tags. This gives you more flexibility as to what your fallback content will be. See below.

<form class="rf-form live" id="rfRadio1">
    <rf-radios api-id="FieldX" name="serviceType" label="What kind of service do you need?" :options="['Repair', 'New replacement or install quote', 'Maintenance visit']" required>
    </rf-radios>
</form>

```html
<rf-radios api-id="FieldX" name="serviceType" label="What kind of service do you need?" :options="['Repair', 'New replacement or install quote', 'Maintenance visit']" required>
    <!-- Fallback content if no options available -->
    <p>Oops. No options available</p>
</rf-radios>
```

<aside class="notice">
    Note: This is (currently) the only field that doesn't need to be wrapped with <code>&lt;div class="rf-field"&gt;</code>
</aside>
