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
        "maliemin-Mstar/MongoSession": "dev-master"
    }
    ...
```

## Usage
```php
require_once('MongoSession.php');

$handler = new MySessionHandler(
	(new MongoClient())->selectCollection('local', 'session'), 
	isset($_COOKIE['session_name']) ? $_COOKIE['session_name'] : null,
	['session_name', 86400 * 365, '/', '.example.com', null, true]
);

session_set_save_handler($handler, true);

session_start();
```
