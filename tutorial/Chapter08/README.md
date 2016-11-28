# Chapter 8: Adding CRUD-L

Let's move our Markdown pages into a database from files and extend the functionality from
retrieve to create, retrieve, update, delete and list (CRUD-L).

We'll use a [SQLite](https://sqlite.org/) database and PHP's [PDO](http://php.net/manual/en/intro.pdo.php)
functions to access it.

`data` dir, add to `.gitignore`

It's the responsiblity of the model to handle interactions with the database so we'll
put the database connection into the model's constructor. The connection has three parameters that
we'll get from our `.env` file (thus keeping them out of our codebase). For the model, we'll need four
properties: an id, a title, the body, and a slug.

```php
class PageModel
{
    protected $db;
    public $id;
    public $title;
    public $body;
    public $slug;

    public function __construct()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `pages` (
    	`id`	INTEGER PRIMARY KEY AUTOINCREMENT,
    	`title`	TEXT,
    	`body`	TEXT,
    	`slug`	TEXT UNIQUE);";
        $this->db = new \PDO(getenv('DSN'), getenv('DB_USER'), getenv('DB_PASSWD'));
        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->db->exec($sql);
    }
}
```

The constructors for 'PageView' and 'PageController' remain unchanged.

```php
class PageView
{
    protected $model;
    protected $twig;
    protected $response;

    public function __construct($model, $twig, $response)
    {
        $this->model = $model;
        $this->twig = $twig;
        $this->response = $response;
    }
}
```

```php
class PageController
{
    protected $twig;
    protected $response;

    public function __construct($twig, $response)
    {
        $this->twig = $twig;
        $this->response = $response;
    }
}
```

## Retrieve

We'll have two retrieve cases: retrieve by id and retrieve by slug. This will give
us two routes.

```
    ['GET', '/page/retrieve/{id}', ['Enigma\Controllers\PageController', 'retrieve']],
    ['GET', '/page/find/{slug}', ['Enigma\Controllers\PageController', 'findBySlug']],
```

In the model we'll add two methods:

```php
public function getByID($id)
{
    $sql = 'SELECT * FROM pages WHERE id = :id';
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetch(\PDO::FETCH_ASSOC);
    $this->setData($data);
}

public function getBySlug($slug)
{
    $sql = 'SELECT * FROM pages WHERE slug = :slug';
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':slug', $slug, \PDO::PARAM_STR);
    $stmt->execute();
    $data = $stmt->fetch(\PDO::FETCH_ASSOC);
    $this->setData($data);
}
```

In the view we'll need just one method:

```php
public function retrieve()
{
    $markdownStr = "# " . $this->model->title . "\n\n" . $this->model->body;

    $converter = new \League\CommonMark\CommonMarkConverter();
    $html = $converter->convertToHtml($markdownStr);
    $this->response->setContent($this->twig->render('page.twig', array('html' => $html)));
}

```

In the controller we'll need two methods:

```php
public function retrieve($params)
{
    $id = $params['id'];
    try {
        $model = new \Enigma\Models\PageModel;
        $model->retrieve($id);
    } catch (\Enigma\FileNotFoundException $e) {
        header("HTTP/1.0 404 Not Found");
        echo $this->twig->render('404.twig');
        exit;
    }
    $view = new \Enigma\Views\View($model, $this->twig, $this->response);
    $view->retrieve();
}

public function findBySlug($params)
{
    $slug = $params['slug'];
    try {
        $model = new \Enigma\Models\PageModel;
        $model->getBySlug($slug);
    } catch (\Enigma\FileNotFoundException $e) {
        header("HTTP/1.0 404 Not Found");
        echo $this->twig->render('404.twig');
        exit;
    }
    $view = new \Enigma\Views\View($model, $this->twig, $this->response);
    $view->retrieve();
}

```


## Delete

route

```
['GET', '/page/delete/{id}', ['Enigma\Controllers\PageController', 'delete']],
```
model

```php
public function delete($id)
{
    $sql = 'DELETE FROM pages WHERE id = :id';
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
    $stmt->execute();
}
```

view
controller

## Create

route

```
['GET', '/page/add', ['Enigma\Controllers\PageController', 'create']],
```
model

```php
public function create($params)
{
    $this->setData($params);
    $sql = 'INSERT INTO pages (title, body, slug) VALUES (:title, :body, :slug)';
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':title', $this->title, \PDO::PARAM_STR);
    $stmt->bindParam(':body', $this->body, \PDO::PARAM_STR);
    $stmt->bindParam(':slug', $this->slug, \PDO::PARAM_STR);
    $stmt->execute();
}
```

view
controller

## Update

route

```
['GET', '/page/edit/{id}', ['Enigma\Controllers\PageController', 'update']],
```

model

```php
public function update($id)
{
    $sql = 'UPDATE pagess SET title = :title, body = :body, slug = :slug WHERE id = :id';
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':title', $this->title, \PDO::PARAM_STR);
    $stmt->bindParam(':body', $this->body, \PDO::PARAM_STR);
    $stmt->bindParam(':slug', $this->slug, \PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
    $stmt->execute();
}
```

view
controller

## List

route

```
['GET', '/page/browse', ['Enigma\Controllers\PageController', 'list']],
```

model

```php
public function getAll()
{
    $sql = 'SELECT * FROM pages';
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
#        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
#        $this->setData($data);
}

```

controller
view
