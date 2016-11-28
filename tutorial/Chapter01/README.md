# Chapter 1: Hello World

Let's start with a web application that simply displays "Hello World!" to help us get
our project set up.

In the project folder let's create two folders:  `public` will be
our web server directory and `src` will be where we put all the code for our
application.  By keeping our source code outside the web server space, it can not
be accidentally exposed to visitors (if, for example, the web server is misconfigured).

We'll be using our `index.php` as a [front controller](http://en.wikipedia.org/wiki/Front_Controller_pattern)
such that all requests to our application will be directed to it.  Inside the `public`
folder, create an `index.php` file containing:
```php
<?php declare(strict_types = 1);

require dirname(__DIR__) . '/src/Bootstrap.php';
```
We do not want to expose anything so all the work is done from `Bootstrap.php`.

In your `src` folder create a `Bootstrap.php` file with the following content:
```php
<?php declare(strict_types = 1);

echo 'Hello World!';
```

Now let's see if everything is set up correctly. Open up a console and navigate
into your projects public folder. In there type `php -S localhost:8000` and
press enter. This will start the built-in webserver and you can access your page
in a browser with http://localhost:8000. You should now see the 'Hello World!'
message.

Now let's save our work to a git repository and the push it to github. See this
[git tutorial](http://kbroman.org/github_tutorial/pages/init.html) if need be.


[Next>>](../Chapter02)
