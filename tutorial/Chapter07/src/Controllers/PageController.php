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
        $slug = $params['slug'];
        try {
            $model = new \Enigma\Models\PageModel;
            $model->retrieve($slug);
        } catch (\Enigma\FileNotFoundException $e) {
            header("HTTP/1.0 404 Not Found");
            echo $this->twig->render('404.twig');
            exit;
        }
        $view = new \Enigma\Views\PageView($model, $this->twig, $this->response);
        $view->show($model->contents());
    }
}
