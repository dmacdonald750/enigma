<?php

namespace Enigma\Models;

class File
{
  public static function retrieve($slug)
  {
    $filename = dirname(dirname(__DIR__)) . '/pages/' . $slug . '.md';
    if (! file_exists($filename)) {
      throw new \Enigma\FileNotFoundException();
    }
    $str = file_get_contents($filename);
    return $str;
  }
}
