# Chapter 6: Routing

We handled route matching ourselves in the last chapter, but let's not waste any
more time re-inventing that wheel when any number of routing packages can handle it for us.

Let's use [FastRoute](https://github.com/nikic/FastRoute) and install it with Composer.

```
composer require nikic/fast-route
```

Now let's rewrite `Bootstrap.php` to use FastRoute. Add the following code after the
section Twig is loaded from. This code is simple registering the available routes for our
application.

```php
$routeDefinitionCallback = function (\FastRoute\RouteCollector $r) {
    $routes = include('Routes.php');
    foreach ($routes as $route) {
        $r->addRoute($route[0], $route[1], $route[2]);
    }
};
```

We'll define the routes in a separate file. Create a `Routes.php` file in the `src`
directory and add:
```php
<?php declare(strict_types = 1);

return [
    ['GET', '/page/{slug}', ['Enigma\Controllers\PageController', 'retrieve']],
    ['GET', '/', ['Enigma\Controllers\HomeController', 'home']],
];
```
Each route is an array with the allowed methods, the url path, and an array with a classname
and a method. If a route is matched, an object having the classname is instantiated and
the method is called. Any url placeholders, `{slug}` for example, are converted to
key-value pairs and passed to the method in an array.

Now we need to add the dispatcher to `Bootstrap.php`

```php
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
```

Now we'll back up and add the `HomeController.php` to handle the homepage.

'''php

'''

[Next>>](Chapter07/)
