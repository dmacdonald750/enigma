# Composer

[Composer](https://getcomposer.org/) is a dependency and package manager for PHP with
packages for Composer available on [Packagist](https://packagist.org/).

A file named `composer.json` located in your project root directory defines your
project and its dependencies. It must be valid [JSON](http://www.json.org/) or Composer will fail.

This is the `composer.json` file for this project with the minimum useful
information.
```json
{
    "name": "dmacdonald750/enigma",
    "description": "A web application generator",
    "license": "MIT",
    "require": {
        "php": ">=7.0.0"
    },
    "autoload": {
      "psr-4": {
        "Enigma\\": "src/"
      }
    }
}
```

After creating the `composer.json` file, open a console window and run `composer update`.
This adds a `vendor` directory to your project where all the PHP packages used by your
project will go.  Add `vendor/` to your `.gitignore` file.

Composer also generates an `autoload.php` file which allows to autoload
all our packages by adding the following to our `Bootstrap.php` file:
```php
require dirname(__DIR__) . '/vendor/autoload.php';
```

Notice that in the `composer.json` file, the `Enigma` namespace points to the `src/`
directory. This adds your code's classes to the autoloader.

You can and should add dependencies on PHP extension libraries to your `composer.json`
file.  For example, if mbstring is required, add
```json
  "require": {
    ...
    "ext-mbstring": ">=7.0.0"
  }
```
You can use `composer show --platform` to see the list of locally installed extension
libraries.
