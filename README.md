# Swiss Free Commerce Connect (Fast CGI Client)

This package is used to connect microservices to each other and can be used to make http requests between them through socket.

Any http request including get, post, put, delete, and patch that they want together can be handled with it.

---

This package is a simplified version of the [hollodotme/fast-cgi-client](https://github.com/hollodotme/fast-cgi-client) package.

Because working with this package was more difficult than expected, we simplified this package and created a JSON connection between microservices as follows.

## Install via composer

Run the following command to pull in the latest version:
```bash
composer require swissfreecommerce/connect
```

## Documentation

### variable name

### Get Request
To send the get request, you must run the following command

```php
use SwissFreeCommerce\Connect\Connection\Json;

$connectJson = new Json(script_file_name, hostname, port);

$connectJson->get('url address', 'array query params', 'another http variable');
```

### Post Request
To send the post request, you must run the following command

```php
use SwissFreeCommerce\Connect\Connection\Json;

$connectJson = new Json(script_file_name, hostname, port);

$connectJson->post('url address', 'array params form data', 'array query params', 'another http variable');
```

### Put Request
To send the put request, you must run the following command

```php
use SwissFreeCommerce\Connect\Connection\Json;

$connectJson = new Json(script_file_name, hostname, port);

$connectJson->put('url address', 'array params form data', 'array query params', 'another http variable');
```

### Output Data
The output of all parts is as follows

```json
{
    'status': 'status request, for example: 200 or 401',
    'body': 'response body object'
}
```
