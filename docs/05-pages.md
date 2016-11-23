 # Dynamic Pages

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
Create a `Models` folder in a `src` subdirectory and add the file `File.php` with contents:
```php
class File
{
  public static function retrieve($slug)
  {
    $filename = dirname(dirname(__DIR__)) . '/pages/' . $slug . '.md';
    if (! file_exists($filename)) {
      throw new \Enigma\FileNotFoundException();
    }
    $str = file_get_contents($filename);
    return $str;
  }
}
```

We'll need a view class that takes a Markdown string, converts it to html, then uses Twig
to render a page. Add a `Views` folder to `src`, and add a `Markdown.php` file with
the content:
```php
class Markdown
{
  protected $twig;

  public function __construct($twig)
  {
    $this->twig = $twig;
  }

  public function show($markdown)
  {
    $converter = new \League\CommonMark\CommonMarkConverter();
    $html = $converter->convertToHtml($markdown);
    echo $this->twig->render('page.twig', array('html' => $html));
  }
```

Finally, we'll need to update `Bootstrap.php` to route requests for these dynamic pages.
```php
$path = strtok($_SERVER['REQUEST_URI'], '?');
$parts = explode("/", $path);
if ($path == "/") {
  echo $twig->render('home.twig', array('name' => 'world'));
} elseif ($parts[1] == 'page') {
  $slug = $parts[2];
  try {
    $markdownStr = \Enigma\Models\File::retrieve($slug);
  } catch (\Enigma\FileNotFoundException $e) {
    header("HTTP/1.0 404 Not Found");
    echo $twig->render('404.twig');
    exit;
  }
  $renderer = new \Enigma\Views\Markdown($twig);
  $renderer->show($markdownStr);
} else {
  header("HTTP/1.0 404 Not Found");
  echo $twig->render('404.twig');
}
```

If the request is not for the home page or for an existing Markdown page then we
respond with a '404 Not Found' page.

[Next>>](06-crud.md)
