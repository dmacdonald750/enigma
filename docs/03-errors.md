# Production vs Development Mode

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


##  Error Handling the Easy Way

Every PHP application needs to handle errors properly.  In development mode you want
errors to generate as much debugging information as possible. However, in production mode
any debugging info is a potential security hazard so you only want to display a simple page
saying that an error has occured. Both are different than PHP's default of showing a
blank page when an error occurs.

A number of packages are available to help your application with error handling. One good one
is [Whoops](https://github.com/filp/whoops), so to install this:
```
composer require filp/whoops
```

Now add the following to `Bootstrap.php`:
```php
error_reporting(E_ALL);
whoops = new \Whoops\Run;
if ($DEBUG) {
  $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
} else {
    $whoops->pushHandler(function($e){
        echo 'Whoops! An error has occurred!';
    });
}
$whoops->register();
```

Note that the PrettyPageHandler is for displaying debug info and NOT displaying a cute
error page for users.

Now test that error handling is working correctly. Add `throw new \Exception;`
to your `Bootstrap.php` file and access http://localhost:8000 in a browser;
replace that by `trigger_error('Whoops');`. Change `debug=1`
to `debug=0` in `.env` and rerun both tests.


## Error Handling the Hard Way
