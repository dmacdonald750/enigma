<?php

namespace Enigma\Views;

use \League\CommonMark\CommonMarkConverter;

class PageView
{
    protected $page;
    protected $twig;

    public function __construct($page, $twig)
    {
        $this->page = $page;
        $this->twig = $twig;
    }

    public function retrieve()
    {
        $markdownStr = "# " . $this->page->title . "\n\n" . $this->page->body;

        $converter = new \League\CommonMark\CommonMarkConverter();
        $html = $converter->convertToHtml($markdownStr);
        echo $this->twig->render('page.twig', array('html' => $html));
    }
}
