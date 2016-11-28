<?php declare(strict_types=1);

namespace Enigma\Views;

class PageView
{
    protected $model;
    protected $twig;
    protected $response;

    public function __construct($model, $twig, $response)
    {
        $this->model = $model;
        $this->twig = $twig;
        $this->response = $response;
    }

    public function show()
    {
        $converter = new \League\CommonMark\CommonMarkConverter();
        $html = $converter->convertToHtml($this->model->contents());
        $this->response->setContent($this->twig->render('page.twig', array('html' => $html)));
    }
}
