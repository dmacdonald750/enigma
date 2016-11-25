<?php

namespace Enigma\Views;

class FileView
{
    protected $file;
    protected $twig;

    public function __construct($file, $twig)
    {
        $this->file = $file;
        $this->twig = $twig;
    }

    public function retrieve()
    {
        echo $this->twig->render($this->file->filename);
    }
}
