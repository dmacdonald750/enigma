<?php declare(strict_types=1);

namespace Enigma\Controllers;

class HomeController
{
    protected $twig;
    protected $response;

    public function __construct($twig, $response)
    {
        $this->twig = $twig;
        $this->response = $response;
    }

    public function home($params = array())
    {
        $this->response->setContent($this->twig->render('home.twig'));
    }
}
