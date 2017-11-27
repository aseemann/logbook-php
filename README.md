# logbook-php

This package provides a PSR compatible Logger to send logs to the logbook server and client, https://github.com/alexgunkel/logbook.
The Logger provides the logger itself and some Adapter to send the logs in several ways.

## Installation

You can install the php component of logbook via git or composer. For both you need composer to
get the dependencies installed which are necessary.

### From github 
```:bash
    ## Cloning git repo
    git clone https://github.com/axel-kummer/logbook-php.git /path/to/checkout
    ## cd in to path
    cd /path/to/checkout
    ## Install dependencies
    composer install --no-dev
```

### From pakageist
```:bash
    composer require axel-kummer/logbook-php
```
## Usage

Basic usage.

First you have to setup the request instance which is used to send the logs.

```:php
//Make a request insatnce
$request = \AxelKummer\LogBook\LoggerUtility::setupRequest(
    \AxelKummer\LogBook\Request\HttpRequest::class,
    'MyApplication',
    'myhost'
    8080
);
```

If it's done you can simply create logger which will have injected the request object.

```:php
//get a logger with injected request instance
$logger = \AxelKummer\LogBook\LoggerUtility::getLogger('MyLogger');

//Use the logger to send messages to the logbook server
$logger->info('My info mesage');
```

You can implement an use your own request class. Your Class have to extend ``\AxelKummer\LogBook\Request\AbstractRequest``
and implements the methods ``sendLog`` and ``getUrl``


