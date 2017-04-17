# Service Titan

Rocket Forms integrates with the Service Titan `bookings/` API. Rocket Forms will send certain form data to Service Titan, which will show up as a new booking on the "Calls" screen. This saves the CSR from having to copy/paste data from an email notification into Service Titan. However, because the call to the Service Titan API may fail silently, the CSRs will still need to keep an eye on the notification emails to ensure leads aren't being missed.

Note, all email notifications/confirmations are still handled by Wufoo and *not* Service Titan. Also, as is the case with Wufoo, data is not really sent to the Service Titan API if Rocket Forms is in any environment other than production. In other words, unless `APP_ENV=production` is set in `rocket-forms/.env`, data will **not** be sent to the Service Titan API. Instead, a fake response will be returned.

## Form fields sent to Service Titan

By default, the Rocket Forms app will look for certain fields to be present in the form submission, and it will pass that data on to Service Titan. If you need to customize how the form data maps to the Service Titan fields, you may override the default method by providing your own before form initialization, e.g.:

```html
<script>
    window.rfOptions.methods.prepareServiceTitanData = function () {
        return {
            name: this.fieldData.firstName + ' ' + this.fieldData.lastName,
            email: this.fieldData.email,
            phone: this.fieldData.phone.replace(/[^\d]/g, ''),
            address: this.fieldData.address,
            city: this.fieldData.city,
            serviceType: this.fieldData.serviceType,
            date: this.fieldData.date,
            time: this.fieldData.time,
            message: this.fieldData.comments,
            serviceCategory: this.fieldData.serviceCategory,
            customerType: 'Residential'
        };
    }
</script>
```

All fields are required to be present unless otherwise indicated.

Field name | Description
---------- | -----------
`serviceType` | Indicates whether request is for repair, maintenance, etc. Not used by ST, but appended <br> to the `message` for CSR.
`name` | Customer name
`email` | Customer email
`phone` | Customer phone in 10-digit format, e.g. `4801231234`
`address` | Customer address
`city` | Customer city
`zip` |  (optional) Customer zip
`date` | Requested service date in format `Ymd` e.g. `20160502`
`message` |  This is mapped to the `summary` field in Service Titan, which is the only field available to describe <br> the customer's problem and what kind of service is being requested.
`time` | (optional), Not used by ST, but appended to the `message` for CSR. See [Appointment times](#appointment-times)
`serviceCategory` | (optional), If `serviceType` is maintenance, this can be used to indicate what kind of maintenance is needed, <br> e.g. HVAC, Plumbing, Electrical, etc. Not used by ST, but appended to the `message` for CSR.
`customerType` | (optional), values may include `Commercial` or `Residential`. Defaults to `Residential`.

## Environment settings

Some additional values may be specified in the Rocket Forms `.env` file. Note that `SERVICE_TITAN_API_KEY` is required when integrating with Service Titan. Other values are optional.

In `project-root/rocket-forms/.env`:

```
SERVICE_TITAN_API_KEY=
CLIENT_TIMEZONE=America/Denver
CLIENT_STATE=CO
CLIENT_ZIP=85234
```

* `SERVICE_TITAN_API_KEY` The client's API key is provided by their Service Titan representative. _Required_.
* `CLIENT_TIMEZONE` The PHP timezone in which the client operates. Service Titan uses this when setting the appointment time. _Optional_. Defaults to `American/New_York`.
* `CLIENT_STATE` The two-digit state abbreviate where the client operates.
* `CLIENT_ZIP` If a client only operates in one zip code, you may specify that here (and them omit that field from the form). If the `zip` field is provided with the form data, it will override this value.

## Debugging

When debugging is enabled (via the `debug` parameter, see [Form parameters](#form-parameters)), the response from Service Titan will appear in the console, along with the one from Wufoo. Look for the `serviceTitanResponse` property. Furthermore, all errors are also logged in `project-root/rocket-forms/storage/lumen.log`. As mentioned before, the Service Titan integration will fail silently; the data will still be sent to Wufoo.

## Appointment times

Currently, Service Titan only offers a single time value for the start of an appointment time (not a range). Given how much client time slots vary, there's no easy way to set the appointment start time in Service Titan, and even if there were, the CSR might still need to see the actual time slot requested by the client. So for now, we just send an 8am service start time to the API, and append the user's actual requested time slot to the `message` field, which shows up for the CSR in the booking summary.
