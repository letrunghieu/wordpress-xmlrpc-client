PHPUnit Testlistener for PHP-VCR
================================

Integrates PHPUnit with [PHP-VCR](http://github.com/php-vcr/php-vcr) using annotations.

![PHP-VCR](https://dl.dropbox.com/u/13186339/blog/php-vcr.png)

Use `@vcr cassette_name` on your tests to turn VCR automatically on and off.

## Usage example

``` php
class VCRTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @vcr unittest_annotation_test
     */
    public function testInterceptsWithAnnotations()
    {
        // Content of tests/fixtures/unittest_annotation_test: "This is a annotation test dummy".
        $result = file_get_contents('http://google.com');
        $this->assertEquals('This is a annotation test dummy.', $result, 'Call was not intercepted (using annotations).');
    }
}
```

## Installation

1) Add to your `composer.json`:

``` json
    "require-dev": {
        "php-vcr/phpunit-testlistener-vcr": "*"
    }
```

2) Install using composer:

``` bash
composer install --dev
```

3) Add listener to your `phpunit.xml`:

``` bash
   <listeners>
      <listener class="PHPUnit_Util_Log_VCR" file="vendor/php-vcr/phpunit-testlistener-vcr/PHPUnit/Util/Log/VCR.php" />
    </listeners>
```

## Dependencies

PHPUnit-Testlistener-VCR depends on:

  * PHP 5.3+
  * [php-vcr/php-vcr](https://github.com/php-vcr/php-vcr)

## Run tests

In order to run all tests you need to get development dependencies using composer:

``` php
composer install --dev
phpunit ./tests
```

## Changelog

 * 2013-05-14 1.0.0: First prototype

## Copyright
Copyright (c) 2013 Adrian Philipp. Released under the terms of the MIT license. See LICENSE for details.

<!--
name of the projects and all sub-modules and libraries (sometimes they are named different and very confusing to new users)
descriptions of all the project, and all sub-modules and libraries
5-line code snippet on how its used (if it's a library)
copyright and licensing information (or "Read LICENSE")
instruction to grab the documentation
instructions to install, configure, and to run the programs
instruction to grab the latest code and detailed instructions to build it (or quick overview and "Read INSTALL")
list of authors or "Read AUTHORS"
instructions to submit bugs, feature requests, submit patches, join mailing list, get announcements, or join the user or dev community in other forms
other contact info (email address, website, company name, address, etc)
a brief history if it's a replacement or a fork of something else
legal notices (crypto stuff)
-->
