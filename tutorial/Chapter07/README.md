# Chapter 7: HTTP

PHP has a lot of built-in functionality for dealing with HTTP. For example, tt has the
[superglobals](http://php.net/manual/en/language.variables.superglobals.php)
that contain request information.

Let's encapsulate the HTTP functionality in objects rather than leaving it scattered
throughout our code.

There are a number of HTTP packages but I find most of them overly complex to work with so let's
write our own, at least for now. We'll just write the functionality we need for our
objects though.

## Request Object

At this point, our code base just needs to get the request method and the request uri.

```php
class Request
{
    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getPath()
    {
        return rawurldecode(strtok($_SERVER['REQUEST_URI'], '?'));
    }
}
```

Now we can plug this into our `Bootstrap.php` file.

```php
$request = new \Enigma\Http\Request;

$routeInfo = $dispatcher->dispatch($request->getMathod(), $request->getPath());
```

## Response Object

Let's update our application such that we can put a `$response->send()` at the end
of `Bootstrap.php`.

[Next>>](Chapter08/)
