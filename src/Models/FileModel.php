<?php

namespace Enigma\Models;

class FileModel
{
    public $filename;

    public function retrieve($slug)
    {
        $this->filename = dirname(dirname(__DIR__)) . '/templates/' . $slug . '.twig';
        if (! file_exists($this->filename)) {
            throw new \Enigma\FileNotFoundException();
        }
    }
}
