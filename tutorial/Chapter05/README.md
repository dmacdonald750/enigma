 # Chapter 5: Dynamic Pages

 Our application only displays a static 'Hello World!' page. Let's add dynamic
 pages generated from [Markdown](http://en.wikipedia.org/wiki/Markdown) files.

 Let's use the [CommonMark](https://github.com/thephpleague/commonmark) Markdown package.
 Install it with Composer
 ```
 composer require league/commonmark
 ```

Now we'll add a Markdown file. Create a `pages` folder in your project root, and add a
`about.md` file to the folder with the content:
```
# Enigma

A web application for generating web applications.
```

Our data model is simply the Markdown file and the action needed is to 'retrieve' it.
Create a `Models` folder in a `src` subdirectory and add the file `PageModel.php` with contents:
```php
class PageModel
{
    protected $filename;

    public function retrieve($slug)
    {
        $this->filename = dirname(dirname(__DIR__)) . '/pages/' . $slug . '.md';
        if (! file_exists($this->filename)) {
            throw new \Enigma\FileNotFoundException();
        }
    }

    public function contents()
    {
        return file_get_contents($this->filename);
    }
}
```

We'll need a view class that takes a Markdown string, converts it to html, then uses Twig
to render a page. Add a `Views` folder to `src`, and add a `PageView.php` file with
the content:
```php
class PageView
{
    protected $model;
    protected $twig;

    public function __construct($model, $twig)
    {
        $this->model = $model;
        $this->twig = $twig;
    }

    public function show()
    {
        $converter = new \League\CommonMark\CommonMarkConverter();
        $html = $converter->convertToHtml($this->model->contents());
        echo $this->twig->render('page.twig', array('html' => $html));
    }
}
```

Next, we'll need to update `Bootstrap.php` to route requests for these dynamic pages.
```php
$path = strtok($_SERVER['REQUEST_URI'], '?');
$parts = explode("/", $path);
if ($path == "/") {
  echo $twig->render('home.twig', array('name' => 'world'));
} elseif ($parts[1] == 'page') {
  $slug = $parts[2];
  try {
      $model = new \Enigma\Models\PageModel;
      $model->retrieve($slug);
  } catch (\Enigma\FileNotFoundException $e) {
      header("HTTP/1.0 404 Not Found");
      echo $this->twig->render('404.twig');
      exit;
  }
  $view = new \Enigma\Views\PageView($model, $this->twig);
  $view->show($model->contents());
} else {
  header("HTTP/1.0 404 Not Found");
  echo $twig->render('404.twig');
}
```

If the request is not for the home page or for an existing Markdown page then we
respond with a '404 Not Found' page.

Finally, let's move the model-views code from `Bootstrap.php` into a controller. Create
a `Controllers` subdirectory under `src` and add the file `PageController.php`
with contents:

```php
class PageController
{
    protected $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function retrieve($params)
    {
        $slug = $params['slug'];
        try {
            $model = new \Enigma\Models\PageModel;
            $model->retrieve($slug);
        } catch (\Enigma\FileNotFoundException $e) {
            header("HTTP/1.0 404 Not Found");
            echo $this->twig->render('404.twig');
            exit;
        }
        $view = new \Enigma\Views\PageView($model, $this->twig);
        $view->show($model->contents());
    }
}
```

In `Bootstrap.php` we call the new controller:

```php
} elseif ($parts[1] == 'page') {
    $class = new \Enigma\Controllers\PageController($twig);
    $class->retrieve(array('slug' => $parts[2]));
}
```


[Next>>](Chapter06/)
