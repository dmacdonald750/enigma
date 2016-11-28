<?php declare(strict_types=1);

namespace Enigma\Views;

class MarkdownFileView
{
    protected $model;
    protected $twig;

    public function __construct($model, $twig)
    {
        $this->model = $model;
        $this->twig = $twig;
    }

    public function show()
    {
        $converter = new \League\CommonMark\CommonMarkConverter();
        $html = $converter->convertToHtml($this->model->contents());
        echo $this->twig->render('page.twig', array('html' => $html));
    }
}
