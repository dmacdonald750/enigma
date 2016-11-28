<?php declare(strict_types=1);

namespace Enigma\Http;

class Request
{
    protected $server;

    public function __construct(array $server)
    {
        $this->server = $server;
    }

    public function getMethod()
    {
        return $this->getServerVariable('REQUEST_METHOD');
    }

    public function getPath()
    {
        return strtok($this->getServerVariable('REQUEST_URI'), '?');
    }

    private function getServerVariable($key)
    {
        #     if (!array_key_exists($key, $this->server)) {
#         throw new MissingRequestMetaVariableException($key);
#     }

     return $this->server[$key];
    }
}
