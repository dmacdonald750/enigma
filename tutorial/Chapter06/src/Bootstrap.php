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

$loader = new \Twig_Loader_Filesystem(dirname(__DIR__) . '/templates');
$twig = new \Twig_Environment($loader);
#echo $twig->render('home.twig');

$routeDefinitionCallback = function (\FastRoute\RouteCollector $r) {
    $routes = include('Routes.php');
    foreach ($routes as $route) {
        $r->addRoute($route[0], $route[1], $route[2]);
    }
};

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$dispatcher = \FastRoute\simpleDispatcher($routeDefinitionCallback);
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // 404 Not Found
        header("HTTP/1.0 404 Not Found");
        echo $twig->render('404.twig');
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        // 405 Method Not Allowed
        header("HTTP/1.0 405 Method Not Allowed");
        echo $twig->render('405.twig');
        break;
    case FastRoute\Dispatcher::FOUND:
        $className = $routeInfo[1][0];
        $method = $routeInfo[1][1];
        $vars = $routeInfo[2];

        $class = new $className($twig);
        $class->$method($vars);
        break;
}

#$path = strtok($_SERVER['REQUEST_URI'], '?');
#$parts = explode("/", $path);
#if ($path == "/") {
#    echo $twig->render('home.twig', array('name' => 'world'));
#} elseif ($parts[1] == 'page') {
#    $class = new \Enigma\Controllers\MarkdownFileController($twig);
#    $class->retrieve(array('slug' => $parts[2]));
#} else {
#    header("HTTP/1.0 404 Not Found");
#    echo $twig->render('404.twig');
#}
