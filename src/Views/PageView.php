<?php

namespace Enigma\Views;

use \League\CommonMark\CommonMarkConverter;

class Markdown
{
  protected $twig;

  public function __construct($twig)
  {
    $this->twig = $twig;
  }

  public function show($markdown)
  {
    $converter = new \League\CommonMark\CommonMarkConverter();
    $html = $converter->convertToHtml($markdown);
    echo $this->twig->render('page.twig', array('html' => $html));
  }
}
