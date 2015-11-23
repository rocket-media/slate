## Textarea

<form class="rf-form live" id="rfTextarea1">
    <div class="rf-field">
        <label class="rf-label" for="message">Message:</label>
        <rf-textarea api-id="Field6" name="message" required :attrs.once="{'data-parsley-error-message': 'Please provide a message'}"></rf-textarea>
    </div>
</form>

```html
<div class="rf-field">
    <label class="rf-label" for="message">Message:</label>
    <rf-textarea api-id="Field6" name="message" required :attrs.once="{'data-parsley-error-message': 'Please provide a message'}"></rf-textarea>
</div>
```