Wordpress XML-RPC PHP Client
=======================

[![Build Status](https://travis-ci.org/letrunghieu/wordpress-xmlrpc-client.svg?branch=master)](https://travis-ci.org/letrunghieu/wordpress-xmlrpc-client) [![Latest Stable Version](https://poser.pugx.org/hieu-le/wordpress-xmlrpc-client/v/stable.svg)](https://packagist.org/packages/hieu-le/wordpress-xmlrpc-client) [![Total Downloads](https://poser.pugx.org/hieu-le/wordpress-xmlrpc-client/downloads.svg)](https://packagist.org/packages/hieu-le/wordpress-xmlrpc-client) [![Latest Unstable Version](https://poser.pugx.org/hieu-le/wordpress-xmlrpc-client/v/unstable.svg)](https://packagist.org/packages/hieu-le/wordpress-xmlrpc-client) [![License](https://poser.pugx.org/hieu-le/wordpress-xmlrpc-client/license.svg)](https://packagist.org/packages/hieu-le/wordpress-xmlrpc-client)

A PHP client for Wordpress websites that closely implement the [XML-RPC WordPress API](http://codex.Wordpress.org/XML-RPC_WordPress_API)

Created by [Hieu Le](http://www.hieule.info)

MIT licensed.

Current version: 2.2.1


## Features
* Full test suit built in supporting testing using your own Wordpress site.
* Support error logging to files with Monolog library.
* Support UTF-8 content.
* Closely implement the whole [XML-RPC WordPress API](http://codex.Wordpress.org/XML-RPC_WordPress_API).
* Detail exception will be thrown when errors occurs.
* (v2.2) Support proxy and http authentication.
* (v2.2.1) Allow value of `DateTime` class to be convert correctly to `datetime.iso8601` XML-RPC type,

## Installation

You will need [Composer](https://getcomposer.org/) installed on your machine to use this library. Verify that composer is installed by typing this command

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
Clone or download the archive of this package from [github](https://github.com/letrunghieu/Wordpress-xmlrpc-client/releases). Copy the package directory into a location of your project. Open the command line terminal and do these command

```bash
cd library/installed/dir
composer install
```

After the installation progress finished, there will be a file called `autoload.php` created inside the `vendor` sub folder of the library. You should include this file to use **Wordpress XML-RPC PHP Client**.


## Usage

All API call will be executed via an instance of the `WordpressClient` class. This is the way we initiate it:

```php
# Your Wordpress website is at: http://wp-website.com
$endpoint = "http://wp-website.com/xmlrpc.php";

# The logger instance
$wpLog = new \Illuminate\Log\Writer(new Monolog\Logger('wp-xmlrpc'));

# Save logs into file
$wpLog->useFiles('path-to-your-log-file');

# Create client instance
# The logger instance is optional
$wpClient = new \HieuLe\WordpressXmlrpcClient\WordpressClient($endpoint, 'username', 'password', $wpLog);
```
To use date time value, you must use an instance of `DateTime` class instead of a string.

There will be 2 types of exception may be thrown from this library:

  * `XmlrpcException`: this kind of exception will be thrown if there is an error when the server executing your request
  * `NetworkException`: this kind of exception will be thrown if there is an error when transfer your request to server or when getting the response.

For API reference, visit [Wordpress documentation](http://codex.Wordpress.org/XML-RPC_WordPress_API) or [Library API documentation](http://letrunghieu.github.io/wordpress-xmlrpc-client/api/index.html)

## Unit testing
By default, the project use recorded data as the default data for test suite. However, if you want to test with your own Wordpress installation, there are available options inside the `./tests/xmlrpc.yml` file:

  * `endpoint`: the url of your Wordpress XML-RPC endpoint
  * `admin_login`: the email or username of a user with the *Administrator* role
  * `admin_password`: the password of the admin user
  * `guest_login`: the email or username of a user with the *Subscriber* role
  * `guest_password`: the password of the guest user

After update the `./tests/xmlrpc.yml` file, run your test again.
