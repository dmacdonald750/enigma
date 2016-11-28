<?php declare(strict_types=1);

namespace Enigma\Models;

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
        $dsn = getenv('DB') . ':' . dirname(dirname(__DIR__)) . getenv('DB_FILE');
        #echo $dsn;
        #exit;
        $this->db = new \PDO($dsn, getenv('DB_USR'), getenv('DB_PSSWD'));
        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->db->exec($sql);
    }

    public function getByID($id)
    {
        $sql = 'SELECT * FROM pages WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($data === false) {
            throw new \Enigma\FileNotFoundException;
        }
        $this->setData($data);
    }

    public function getBySlug($slug)
    {
        $sql = 'SELECT * FROM pages WHERE slug = :slug';
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':slug', $slug, \PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($data === false) {
            throw new \Enigma\FileNotFoundException;
        }
        $this->setData($data);
    }

    public function getAll()
    {
        $sql = 'SELECT * FROM pages LIMIT 10';
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS);
#        $this->setData($data);
    }

    public function delete($id)
    {
        $sql = 'DELETE FROM pages WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
    }

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

    public function update($params)
    {
        $this->setData($params);
        $sql = 'UPDATE pages SET title = :title, body = :body, slug = :slug WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':title', $this->title, \PDO::PARAM_STR);
        $stmt->bindParam(':body', $this->body, \PDO::PARAM_STR);
        $stmt->bindParam(':slug', $this->slug, \PDO::PARAM_STR);
        $stmt->bindParam(':id', $this->id, \PDO::PARAM_INT);
        $stmt->execute();
    }

    public function setData($data = array())
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function getData()
    {
        $properties = get_object_vars($this);
        return $properties;
    }
}
