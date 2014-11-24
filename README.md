MongoSession
====

A PHP MongoDB Session Handler.

## Dependency
* PHP 5.4+
* 64-bit Linux System

## Installation
```
Append dependency into composer.json
    ...
    "require": {
        ...
        "maliemin/mongo-session": "dev-master"
    }
    ...
```

## Usage
Create a TTL index on 'created_at' field in mongodb
```php
require_once('MongoSession.php');

$handler = new MySessionHandler(
	(new MongoClient())->selectCollection('local', 'session'), //collection
	isset($_COOKIE['session_name']) ? $_COOKIE['session_name'] : null, //session id
	['session_name', 86400 * 365, '/', '.example.com', null, true] //parameters for setcookie function
);

session_set_save_handler($handler, true);

session_start();
```
