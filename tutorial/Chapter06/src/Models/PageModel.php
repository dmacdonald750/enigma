<?php declare(strict_types=1);

namespace Enigma\Models;

class PageModel
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
