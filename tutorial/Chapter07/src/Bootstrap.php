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

$request = new \Enigma\Http\Request;
$response = new \Enigma\Http\Response;

$loader = new \Twig_Loader_Filesystem(dirname(__DIR__) . '/templates');
$twig = new \Twig_Environment($loader);

$routeDefinitionCallback = function (\FastRoute\RouteCollector $r) {
    $routes = include('Routes.php');
    foreach ($routes as $route) {
        $r->addRoute($route[0], $route[1], $route[2]);
    }
};

$dispatcher = \FastRoute\simpleDispatcher($routeDefinitionCallback);
$routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getPath());
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

        $class = new $className($twig, $response);
        $class->$method($vars);
        break;
}

$response->send();
