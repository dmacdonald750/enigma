# Chapter 4: Views

[Model-View-Controller](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller)
(MVC) is a popular [design pattern](https://en.wikipedia.org/wiki/Design_pattern)
for developing web applications. It separates the modeling of the domain, the
presentation, and actions based on the url into three separate concerns.  This
separation of concerns is the primary reason PHP web frameworks use templates for their
presentation layer.

## Templates

PHP is already a templating language so we could just use it for our presentation
layer. However, using a separate template engine helps us separate application logic
from the presentation layer because these engines can do little more than display
values of variables.

The PHP world has any number of template engines to choose from, but let's just pick
one of more popular ones: [Twig](http://twig.sensiolabs.org/). Use Composer to install it:

```
 composer require twig/twig:~1.0
 ```
Let's create a folder `templates` to keep our templates. Now create a simple `home.twig`
template to display 'Hello World'.
```
<h1>Hello world!</h1>
```
Finally, update `Bootstrap.php` to intialize Twig and render our new template.
```php
$loader = new \Twig_Loader_Filesystem(dirname(__DIR__) . '/templates');
$twig = new \Twig_Environment($loader);
echo $twig->render('home.twig');
```

## Layout

Generally, a multi-page web site will want to use a base template (or layout)
that contains all the common elements of the site and defines blocks that child
templates can override. Let's add a layout template.
```
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Enigma</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    </head>
    <body>
      <div class="content">
          {% block content %}
          {% endblock %}
      </div>
    </body>
</html>
```
We use [Normalize](https://github.com/necolas/normalize.css) to "normalize" styles
for a wide range of elements and to correct other inconsistencies across browsers.

Now update the `home.twig` template to inherit the layout.
```
{% extends "layout.twig" %}
{% block content %}
<h1>Hello world!</h1>
{% endblock %}
```

When Twig renders `home.twig` it loads `layout.twig` and then replaces the 'content'
block in `layout.twig` with the 'content' block from `home.twig`.

## Themes

A theme is the CSS we use to stylize the layout. Here we can set text sizes and colors, column
widths, background colors, etc. We'll come back to this in later chapters, but for now
we'll just add a simple `style.css` and `css` folder to our `public` directory.  Finally, add the `style.css`
file to the stylesheets in `layout.twig`.
```css
body {
    color: #777;
}

.content {
    margin: 0 auto;
    padding: 0 2em;
    max-width: 800px;
    margin-bottom: 50px;
    line-height: 1.6em;
}
```

[Next>>](..\Chapter05)
