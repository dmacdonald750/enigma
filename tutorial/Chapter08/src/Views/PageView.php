<?php declare(strict_types=1);

namespace Enigma\Views;

class PageView
{
    protected $twig;
    protected $response;

    public function __construct($twig, $response)
    {
        $this->twig = $twig;
        $this->response = $response;
    }

    public function retrieve($model)
    {
        $markdownStr = "# " . $model->title . "\n\n" . $model->body;

        $converter = new \League\CommonMark\CommonMarkConverter();
        $html = $converter->convertToHtml($markdownStr);
        $this->response->setContent($this->twig->render('page.twig', array('html' => $html)));
    }

    public function create($model)
    {
        $this->response->setContent($this->twig->render('create.twig', array('page' => $model)));
    }

    public function update($model)
    {
        $this->response->setContent($this->twig->render('create.twig', array('page' => $model)));
    }

    public function list($modelList)
    {
        $this->response->setContent($this->twig->render('list.twig', array('models' => $modelList)));
    }
}
