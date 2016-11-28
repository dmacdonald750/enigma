# Chapter 3: Error Handling

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
$whoops = new \Whoops\Run;
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


As an exercise, let's write our own class for error handling. We have two functions
we need to deal with, [set_error_handler](http://php.net/manual/en/function.set-error-handler.php)
and [set_exception_handler](http://php.net/manual/en/function.set-exception-handler.php), so let's
wrap a class around them knowing that we need to handle production and development
modes differently.

```php
final class ErrorHandler
{
  private $debug;

  public function __construct($debug)
  {
    $this->debug = $debug;
    error_reporting(E_ALL);
  }

  public function register()
  {
    if ($this->debug) {
      set_error_handler(array($this, 'myDebugErrorHandler'));
      set_exception_handler(array($this, 'myDebugExceptionHandler'));
    } else {
      set_error_handler(array($this, 'myErrorHandler'));
      set_exception_handler(array($this, 'myExceptionHandler'));
    }
  }

  public function myErrorHandler($errno, $errstr) {}

  public function myDebugErrorHandler($errno, $errstr) {}

  public function myExceptionHandler($exception) {}

  public function myDebugExceptionHandler($exception){}
}
```

In `myExceptionHandler`, we are dealing with exceptions that have not been otherwise
caught and so we have an exceptional condition that must halt the application. The only
purpose of `myExceptionHandler` then is to halt gracefully. In production mode, we should
show an http 500 error page; a stack trace in development mode.

```php
public function myExceptionHandler($exception)
{
  header("HTTP/1.0 500 Internal Server Error");
  include("500.html");
  exit;
}

public function myDebugExceptionHandler($exception)
{
  echo "<div>Uncaught exception: " , $exception->getMessage(), " ";
  echo 'thrown in ' . $exception->getFile() . ' on line ' . $exception->getLine() . '</div>';
  echo 'Stack trace:<pre>' . $exception->getTraceAsString() . '</pre>';
  exit;
}
```

The `500.html` error page simply says 'Whoops! An error occurred.'

In `MyErrorHandler`, we are generally dealing with errors invoked by
[trigger_error()](http://php.net/manual/en/function.trigger-error.php) so our function must
handle different error types: errors, warnings and notices.  In debug mode, we
can halt on any error type but in production
we only want to halt on errors while warnings and notices allow execution to continue.

```php
public function myErrorHandler($errno, $errstr, $errFile, $errLine, $errContext)
{
  switch ($errno) {
    case E_USER_ERROR:
      header("HTTP/1.0 500 Internal Server Error");
      include("500.html");
      exit;
    case E_USER_WARNING:
    case E_USER_NOTICE:
    default:
  }
  /* Don't execute PHP internal error handler */
  return true;
}

public function myDebugErrorHandler($errno, $errstr, $errFile, $errLine, $errContext)
{
  switch ($errno) {
    case E_USER_ERROR:
      $msg = "ERROR";
      break;
    case E_USER_WARNING:
      $msg = "WARNING";
      break;
    case E_USER_NOTICE:
      $msg= "NOTICE";
      break;
    default:
      $msg = "Unknown error type";
      break;
  }
  echo "<div>$msg [$errno] $errstr: Line $errLine in file $errFile</div>\n";
  print_r($errContext);
  die;
}

```

Now in `Bootstrap.php` we simply need:

```php
$handlers = new \Enigma\ErrorHandler($DEBUG);
$handlers->register();
```

[Next>>](Chapter04/)
