<?php declare(strict_types=1);

namespace Enigma\Http;

class Request
{
    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getPath()
    {
        return rawurldecode(strtok($_SERVER['REQUEST_URI'], '?'));
    }
}
