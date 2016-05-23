---
title: Rocket Forms Documentation

toc_footers:
  - <a href='http://github.com/tripit/slate'>Documentation Powered by Slate</a>

includes:
  - installation
  - updating
  - configuration
  - deployment
  - usage
  - components/text
  - components/textarea
  - components/select
  - components/datepicker
  - components/radio
  - components/checkbox
  - sections
  - service-titan
  - local-development
  - tests

search: true
---

# Introduction

Rocket Forms is a custom form solution powered by Vue.js on the frontend, the Lumen framework on the backend, and it integrates with Wufoo's API. The form is composed of custom HTML elements like `<rf-textarea>`.

```html
<!-- A sample field -->
<rf-text api-id="Field1" name="name" required :attrs="{'data-parsley-required-message': 'Please provide your name'}"></rf-text>
```

## App Structure

The Rocket Forms app has three main components:

1. **The backend**, which is a tiny application powered by [Lumen](http://lumen.laravel.com/).
    * Receives XHR requests from a forms page frontend.
    * Validates that the request came from a legitimate source (e.g. an actual forms page, and not a POST request from a spammer).
    * If everything authenticates, it sends the form data to Wufoo. The request key is then destroyed.
    * Responds with errors when appropriate or passes back errors received from the Wufoo API. Request keys remain valid for 5 errorneous requests before they are destroyed.
2. **The frontend** form components and submission logic which are built with [Vue.js](http://vuejs.org/).
    * Provides a nice modularized way of building form components with two-way data binding, validation, etc.
    * Submits the form data to the backend.
    * Uses [Parsley.js](http://parsleyjs.org/) for validation.
3. The **Craft CMS plugin**.
    * Communicates with the backend to retrieve a request key which it then supplies to the frontend via a template variable. The frontend then submits this (back) to the backend in order to validate the request.
    * This plugin exists entirely to prevent someone from spamming the form system.

# Demo

See the demo page at [http://rf.rocketmedia.com/demo/demo.html](http://rf.rocketmedia.com/demo/demo.html)
