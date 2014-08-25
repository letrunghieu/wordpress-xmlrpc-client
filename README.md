Wordpress XML-RPC PHP Client
=======================

[![Build Status](https://travis-ci.org/letrunghieu/wordpress-xmlrpc-client.svg?branch=master)](https://travis-ci.org/letrunghieu/wordpress-xmlrpc-client) [![Latest Stable Version](https://poser.pugx.org/hieu-le/wordpress-xmlrpc-client/v/stable.svg)](https://packagist.org/packages/hieu-le/wordpress-xmlrpc-client) [![Total Downloads](https://poser.pugx.org/hieu-le/wordpress-xmlrpc-client/downloads.svg)](https://packagist.org/packages/hieu-le/wordpress-xmlrpc-client) [![Latest Unstable Version](https://poser.pugx.org/hieu-le/wordpress-xmlrpc-client/v/unstable.svg)](https://packagist.org/packages/hieu-le/wordpress-xmlrpc-client) [![License](https://poser.pugx.org/hieu-le/wordpress-xmlrpc-client/license.svg)](https://packagist.org/packages/hieu-le/wordpress-xmlrpc-client)

A PHP client for Wordpress websites that closely implement the [XML-RPC WordPress API](http://codex.Wordpress.org/XML-RPC_WordPress_API)

Created by [Hieu Le](http://www.hieule.info)

MIT licensed.

Current version: 2.4.0


## Features

* Full test suit built in supporting testing using your own Wordpress site.
* ~~Support error logging to files with Monolog library.~~ Now, erros can be logged in a more felxible way via **error callbacks** (v 2.4.0)
* Support UTF-8 content.
* Closely implement the whole [XML-RPC WordPress API](http://codex.Wordpress.org/XML-RPC_WordPress_API).
* Detail exception will be thrown when errors occurs.
* (v2.2) Support proxy and http authentication.
* (v2.2.1) Allow value of `DateTime` class to be convert correctly to `datetime.iso8601` XML-RPC type,
* (v2.4.0) Support using custom User Agent string beside the default User Agent string.
* (v2.4.0) Support callbacks on **sending** and **error** events

## Installation

~~You will need [Composer](https://getcomposer.org/) installed on your machine to use this library~~ [Composer](https://getcomposer.org/) now is not required but recommended. Verify that composer is installed by typing this command

```bash
composer --version
```

Choose one of the following methods to install **Wordpress XML-RPC PHP Client**

### Your project has used composer:

Add this dependency into your `composer.json` file

```json
"hieu-le/wordpress-xmlrpc-client":"~2.0"
```

After that, run `composer update` to install this package.

### Your project does not use composer:

Clone or download the archive of this package from [github](https://github.com/letrunghieu/Wordpress-xmlrpc-client/releases). Include all files in the `src` directory into your project and start using **Wordpress XML-RPC Client**. You have to update the code of this library manually if using it without Composer.

Required PHP extension is `xmlrpc` extension. The `curl` extension is optional be recommended.


## Usage

All API call will be executed via an instance of the `WordpressClient` class. Since version 2.4.0, there is no mandatory parameters in the contructor. `endPoint`, `username`, and `password` can be updated anytime by calling `setCredentials` method. The last parameter in previous version contructor (which is an instance of `\Illuminate\Log\Writer` class) is deprecated and will be removed in the next major release. Below is an example of using this library:

```php
# Your Wordpress website is at: http://wp-website.com
$endpoint = "http://wp-website.com/xmlrpc.php";

# The Monolog logger instance
$wpLog = new Monolog\Logger('wp-xmlrpc');

# Create client instance
$wpClient = new \HieuLe\WordpressXmlrpcClient\WordpressClient();
# Log error
$wpClient->onError(function($error, $event) use ($wpLog){
    $wpLog->addError($error, $event);
});

# Set the credentials for the next requests
$wpClient->setCredentials($endpoint, 'username', 'password');

```

If you have used logging feture of previous version of this library, you should update your code to use the new way of loggin as above, the Monolog instance can be replaced by any kinds of logging tool that you have.

To use date time value, you must use an instance of `DateTime` class instead of a string.

There will be 2 types of exception may be thrown from this library:

  * `XmlrpcException`: this kind of exception will be thrown if there is an error when the server executing your request
  * `NetworkException`: this kind of exception will be thrown if there is an error when transfer your request to server or when getting the response.

For API reference, visit [Wordpress documentation](http://codex.Wordpress.org/XML-RPC_WordPress_API) or [Library API documentation](http://letrunghieu.github.io/wordpress-xmlrpc-client/api/index.html)

## User Agent (since 2.4.0)

The library use the default User Agent when contacting with Wordpress blogs. If you want to use onother one, pass your custom User Agent string into the `setUserAgent` method. If you passed a _falsy_ value (`null`, `false`, ...) the default one will be used (thank @WarrenMoore)

## Callbacks and events (since 2.4.0)

The library allow developers to listen on two events `Sending` and `Error`. You can add new closure as a callback for each events by calling `on<event>` method with the closure as parameter (see the `onError` example above).

### `onSending($event)`

This event is fired before each request is send to Wordpress blogs. `$event` is an array:

- `event`: the name of the event, here is `sending`
- `endpoint`: URL of the current endpoint
- `username`: current username
- `password`: current password
- `method`: current XML-RPC method
- `params`: parameters passed to the current method
- `request`: the body of the current request which will be sent
- `proxy`: current proxy config
- `auth`: current http auth config

### `onError($errorMessage, $event)`

This event is fired when the library run into errors, before any exception thrown. `$errorMessage` is a string. `$event` is an array:

- `event`: the name of the event, here is `sending`
- `endpoint`: URL of the current endpoint
- `request`: the body of the current request
- `proxy`: current proxy config
- `auth`: current http auth config


## Unit testing

By default, the project use recorded data as the default data for test suite. However, if you want to test with your own Wordpress installation, there are available options inside the `./tests/xmlrpc.yml` file:

  * `endpoint`: the url of your Wordpress XML-RPC endpoint
  * `admin_login`: the email or username of a user with the *Administrator* role
  * `admin_password`: the password of the admin user
  * `guest_login`: the email or username of a user with the *Subscriber* role
  * `guest_password`: the password of the guest user

After update the `./tests/xmlrpc.yml` file, run your test again.
