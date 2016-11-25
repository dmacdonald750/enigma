<?php

namespace Enigma\Views;

use \League\CommonMark\CommonMarkConverter;

class MarkFileView
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
        $converter = new \League\CommonMark\CommonMarkConverter();
        $html = $converter->convertToHtml($this->file->contents());
        echo $this->twig->render('page.twig', array('html' => $html));
    }
}
