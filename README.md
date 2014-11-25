ssdb-session
====

A PHP SSDB Session Handler.

## Dependency
* PHP 5.5+
* [ssdb/phpssdb](https://github.com/ssdb/phpssdb)

## Installation
```
Append dependency into composer.json
    ...
    "require": {
        ...
        "maliemin/ssdb-session": "dev-master"
    }
    ...
```

## Usage
```php
$ssdb = new SSDB\Client('127.0.0.1', 8888);

$handler = new SSDBSession\SessionHandler($ssdb);

session_set_save_handler($handler, true);

session_start();
```
