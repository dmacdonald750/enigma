<?php declare(strict_types=1);

namespace Enigma\Http;

class Response
{
    private $version = '1.1';
    private $statusCode = 200;
    private $statusText = 'OK';
    private $content;
    private $statusTexts = [
        200 => 'OK',
        301 => 'Moved Permanently',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        500 => 'Internal Server Error',
    ];

    public function setStatusCode($code)
    {
        $this->statusCode = $code;
        if (array_key_exists((int) $code, $this->statusTexts)) {
            $this->statusText = $this->statusTexts[$code];
        }
    }

    public function redirect($url)
    {
        header('Location: ' .  $url);
        exit;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function send()
    {
        header(trim(sprintf(
            'HTTP/%s %s %s',
            $this->version,
            $this->statusCode,
            $this->statusText
        )));
        echo $this->content;
        #exit;
    }
}
