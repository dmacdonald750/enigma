<?php declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = new \Dotenv\Dotenv(dirname(__DIR__));
$dotenv->load();
$DEBUG = getenv('debug');

#error_reporting(E_ALL);
#$whoops = new \Whoops\Run;
#if ($DEBUG) {
#    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
#} else {
#    $whoops->pushHandler(function ($e) {
#        echo 'Whoops! An error has occurred!';
#    });
#}
#$whoops->register();

$handlers = new \Enigma\ErrorHandler($DEBUG);
$handlers->register();

#throw new \Exception('DOH!!');
#trigger_error('HA HA', E_USER_ERROR);

echo 'Hello World!';
