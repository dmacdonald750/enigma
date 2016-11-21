# Error Handling

## Environment Variables

Most applications will have variables that define how the application behaves
in production mode versus how it behaves in development mode. Setting these variables
in your source code will create endless headaches when using a version control system
(like Github).

One solution is to define these variables as environment variables. Let's use the
package [PHP Dotenv](https://github.com/vlucas/phpdotenv) to manage these. Install
the package (and add as a dependency in `composer.json`) with Composer:
```
composer require vlucas/phpdotenv
```

The packages uses a `.env` file to define variables so add this file your project root
directory and add `debug=1` to the file. Now add `.env` to the `.gitignore` file. In
the `Bootstrap.php` file, add:
```php
$dotenv = new \Dotenv\Dotenv(dirname(__DIR__));
$dotenv->load();
$DEBUG = getenv('debug');
```

The `.env` file is also a good place to put credentials for database connections.
