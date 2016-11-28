<?php

class PageController
{
    protected $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function findBySlug($params)
    {
        try {
            $page = new \Enigma\Models\PageModel;
            $page->getBySlug($slug);
        } catch (\Enigma\FileNotFoundException $e) {
            header("HTTP/1.0 404 Not Found");
            echo $this->twig->render('404.twig');
            exit;
        }
        $renderer = new \Enigma\Views\PageView($page, $this->twig);
        $renderer->retrieve();
    }

    public function retrieve($params)
    {
    }

    public function browse($params)
    {
    }

    public function create($params)
    {
    }

    public function update($params)
    {
    }

    public function delete($params)
    {
    }
}
