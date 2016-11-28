<?php declare(strict_types=1);

namespace Enigma\Http;

class Response
{
    private $content;

    public function __construct()
    {
    }

    public function redirect($url)
    {
        $this->setHeader('Location', $url);
    #$this->setStatusCode(301);
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function send()
    {
    }
}
