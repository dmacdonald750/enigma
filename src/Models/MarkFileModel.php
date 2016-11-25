<?php

namespace Enigma\Models;

class MarkFileModel
{
    protected $filename;

    public function retrieve($slug)
    {
        $this->filename = dirname(dirname(__DIR__)) . '/pages/' . $slug . '.md';
        if (! file_exists($this->filename)) {
            throw new \Enigma\FileNotFoundException();
        }
    }

    public function contents()
    {
        return file_get_contents($this->filename);
    }
}
