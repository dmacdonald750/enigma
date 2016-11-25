<?php

class MarkFileController
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
            $file = new \Enigma\Models\MarkFileModel;
            $file->retrieve($slug);
        } catch (\Enigma\FileNotFoundException $e) {
            header("HTTP/1.0 404 Not Found");
            echo $this->twig->render('404.twig');
            exit;
        }
        $renderer = new \Enigma\Views\MarkFileView($file, $this->twig);
        $renderer->retrieve($file->contents());
    }
}
