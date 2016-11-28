<?php declare(strict_types=1);

namespace Enigma\Controllers;

class HomeController
{
    protected $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function home($params = array())
    {
        echo $this->twig->render('home.twig');
    }
}
