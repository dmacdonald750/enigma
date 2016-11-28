<?php declare(strict_types=1);

namespace Enigma\Controllers;

class PageController
{
    protected $twig;
    protected $response;

    public function __construct($twig, $response)
    {
        $this->twig = $twig;
        $this->response = $response;
    }

    public function retrieve($params)
    {
        $id = $params['id'];
        try {
            $model = new \Enigma\Models\PageModel;
            $model->getByID($id);
        } catch (\Enigma\FileNotFoundException $e) {
            header("HTTP/1.0 404 Not Found");
            echo $this->twig->render('404.twig');
            exit;
        }
        $view = new \Enigma\Views\PageView($this->twig, $this->response);
        $view->retrieve($model);
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
        $view = new \Enigma\Views\PageView($this->twig, $this->response);
        $view->retrieve($model);
    }

    public function delete($params)
    {
        $id = $params['id'];
        $model = new \Enigma\Models\PageModel;
        $model->delete($id);
    }

    public function create($params)
    {
        if (isset($_POST['submit'])) {
            $model = new \Enigma\Models\PageModel;
            $model->create($_POST);
        } else {
            $model = new \Enigma\Models\PageModel;
            $view = new \Enigma\Views\PageView($this->twig, $this->response);
            $view->create($model);
        }
    }

    public function update($params)
    {
        if (isset($_POST['submit'])) {
            $model = new \Enigma\Models\PageModel;
            $model->update($_POST);
        } else {
            $model = new \Enigma\Models\PageModel;
            $model->getByID($params['id']);
            $view = new \Enigma\Views\PageView($this->twig, $this->response);
            $view->update($model);
        }
    }

    public function list($params)
    {
        $model = new \Enigma\Models\PageModel;
        $data = $model->getAll();
        $view = new \Enigma\Views\PageView($this->twig, $this->response);
        $view->list($data);
    }
}
