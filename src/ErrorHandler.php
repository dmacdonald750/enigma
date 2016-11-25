<?php declare(strict_types=1);

namespace Enigma;

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
        echo "<div>$msg [$errno] $errstr: $errline in file $errfile</div>\n";
        print_r($errContext);
        die;
    /* Don't execute PHP internal error handler */
    #return true;
    }

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
}
