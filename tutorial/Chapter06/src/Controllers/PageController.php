<?php declare(strict_types=1);

namespace Enigma\Controllers;

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
