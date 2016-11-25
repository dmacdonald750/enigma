<?php

class FileController
{
    protected $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function home($params)
    {
        $this->retrieve(array('slug' => 'home'));
    }

    public function retrieve($params)
    {
        $slug = $params['slug'];
        try {
            $file = new \Enigma\Models\FileModel;
            $file->retrieve($slug);
        } catch (\Enigma\FileNotFoundException $e) {
            header("HTTP/1.0 404 Not Found");
            echo $this->twig->render('404.twig');
            exit;
        }
        $renderer = new \Enigma\Views\FileView($file, $this->twig);
        $renderer->retrieve($file->contents());
    }
}
