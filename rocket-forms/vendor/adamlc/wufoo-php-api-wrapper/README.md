Wufoo PHP API Wrapper
=============
[![Latest Stable Version](https://poser.pugx.org/adamlc/wufoo-php-api-wrapper/v/stable.png)](https://packagist.org/packages/adamlc/wufoo-php-api-wrapper) [![Total Downloads](https://poser.pugx.org/adamlc/wufoo-php-api-wrapper/downloads.png)](https://packagist.org/packages/adamlc/wufoo-php-api-wrapper) [![Latest Unstable Version](https://poser.pugx.org/adamlc/wufoo-php-api-wrapper/v/unstable.png)](https://packagist.org/packages/adamlc/wufoo-php-api-wrapper) [![License](https://poser.pugx.org/adamlc/wufoo-php-api-wrapper/license.png)](https://packagist.org/packages/adamlc/wufoo-php-api-wrapper)

This is a fork of the original Wufoo PHP library. This fork has tried to make the library more modern by using proper name spaces and exceptions. This class follows PSR2 and PSR4.

Chances are I have probably broken some stuff. I haven't tested everything and there is still work to do, but its a start....

The Wufoo PHP API plugin is meant to help make working with the Wufoo API easier for PHP developers. It doesn't do anything that working directly with the API can't do, it just provides an abstraction layer making getting the information you need easier.

### Composer

To install Wufoo PHP API Wrapper as a Composer package add this to your composer.json:

```json
"adamlc/wufoo-php-api-wrapper": "dev-master"
```

Run `composer update`
### Basics

The API Wrapper is a collection of functions each for using a specific API. For example, this is how you would get data on your account's users:

```php
$wrapper = new Adamlc\Wufoo\WufooApiWrapper($apiKey, $subdomain);
print_r($wrapper->getUsers());
```

Some APIs need more information to be able to return the information they are supposed to. For example, the Entries API needs to know what form to return data from. Each will be documented below.

### Full API Documentation

Available here: http://wufoo.com/docs/api/v3/

Each API returns its own set of specific information which is all documented on Wufoo.com for reference.

### Users

Information about all users:

```php
$wrapper = new Adamlc\Wufoo\WufooApiWrapper('KUUI-22JI-ENID-IREW', 'yoursubdomain')); //create the class
print_r($wrapper->getUsers());
```

Full documentation: http://wufoo.com/docs/api/v3/users/

### Forms

Information about all forms:

```php
$wrapper = new Adamlc\Wufoo\WufooApiWrapper('KUUI-22JI-ENID-IREW', 'yoursubdomain'); //create the class
print_r($wrapper->getForms($identifier = null)); //No identifier needed to retrieve all forms, otherwise pass in a form URL or hash
```

Information about a specific form:

```php
$wrapper = new Adamlc\Wufoo\WufooApiWrapper('KUUI-22JI-ENID-IREW', 'yoursubdomain'); //create the class
print_r($wrapper->getForms($identifier = 'k4j9jw')); //Identifier can be either a form hash or form URL.
```

Full documentation: http://wufoo.com/docs/api/v3/forms/

### Entries

Entries from a form:

```php
$wrapper = new Adamlc\Wufoo\WufooApiWrapper('KUUI-22JI-ENID-IREW', 'yoursubdomain'); //create the class
print_r($wrapper->getEntries('k4j9jw', 'forms', 'Filter1=EntryId+Is_equal_to+1')); //Notice the filter
```

Entries from a report:

```php
$wrapper = new Adamlc\Wufoo\WufooApiWrapper('KUUI-22JI-ENID-IREW', 'yoursubdomain'); //create the class
print_r($wrapper->getReportEntries('k4j9jw', 'Filter1=EntryId+Is_equal_to+1')); //Notice the filter
```

Entries POST to a form:

```php
$wrapper = new Adamlc\Wufoo\WufooApiWrapper('KUUI-22JI-ENID-IREW', 'yoursubdomain'); //create the class

//NOTE: Create WufooSubmitFields for the $postArray values
$postArray = array(new Adamlc\Wufoo\ValueObject\WufooSubmitField('Field1', 'Booyah!'), new Adamlc\Wufoo\ValueObject\WufooSubmitField('Field1', '/files/myFile.txt', $isFile = true));
print_r($wrapper->entryPost('f83u4d', $postArray));
```

Full documentation: http://wufoo.com/docs/api/v3/forms/post/

Full documentation: http://wufoo.com/docs/api/v3/entries/

### Fields

Fields of a form:

```php
$wrapper = new Adamlc\Wufoo\WufooApiWrapper('KUUI-22JI-ENID-IREW', 'yoursubdomain'); //create the class
print_r($wrapper->getFields('j9js9r')); //Identifier is a form URL or hash
```

Fields of a report:

```php
$wrapper = new Adamlc\Wufoo\WufooApiWrapper('KUUI-22JI-ENID-IREW', 'yoursubdomain'); //create the class
print_r($wrapper->getReportFields('j9js9r')); //Identifier is a reporyt URL or hash
```

Bear in mind that fields may have `SubFields`, as is the case when using Wufoo-provided fields like Name, which has First and Last as subfields. Testing for SubFields and looping through those within the main loop while processing the data is a good idea.

Full documentation: http://wufoo.com/docs/api/v3/fields/

### Comments

Comments are entered in the Wufoo.com Entry Manager.

Get comments from a form:

```php
$wrapper = new Adamlc\Wufoo\WufooApiWrapper('KUUI-22JI-ENID-IREW', 'yoursubdomain'); //create the class
print_r($wrapper->getComments('j9js9r', $entryId = '1')); //You may remove the $entryId parameter to get all comments for a form by EntryId.
```

Full documentation: http://wufoo.com/docs/api/v3/comments/

### Reports

Information about all reports:

```php
$wrapper = new Adamlc\Wufoo\WufooApiWrapper('KUUI-22JI-ENID-IREW', 'yoursubdomain'); //create the class
print_r($wrapper->getReports());
```

Information about single form:

```php
$wrapper = new Adamlc\Wufoo\WufooApiWrapper('KUUI-22JI-ENID-IREW', 'yoursubdomain'); //create the class
print_r($wrapper->getReports('a5u8r9'));
```

Full documentation: http://wufoo.com/docs/api/v3/reports/

###Web Hook

Add a Web Hook to a form:

```php
$wrapper = new Adamlc\Wufoo\WufooApiWrapper('KUUI-22JI-ENID-IREW', 'yoursubdomain'); //create the class
print_r($wrapper->webHookPut('a5u8r9', 'http://coolguy.com/webhooker/', 'key', $metadata = false);
```

Delete a Web Hook from a form:

```php
$wrapper = new Adamlc\Wufoo\WufooApiWrapper('KUUI-22JI-ENID-IREW', 'yoursubdomain'); //create the class
print_r($wrapper->webHookDelete($formIdentifier = '432j83j', $hash = 'a5u8r9'));
```

Full documentation: http://wufoo.com/docs/api/v3/webhooks/
